<?php

namespace Tests\Feature\Hospital;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use App\Services\DonorMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonorMatchingDistanceTest extends TestCase
{
    use RefreshDatabase;

    // Colombo General Hospital's real-ish coordinates, used as the fixed
    // hospital location across these tests.
    private const HOSPITAL_LAT = 6.9271;
    private const HOSPITAL_LNG = 79.8612;

    private function makeRequest(Hospital $hospital, string $urgency = 'standard'): BloodRequest
    {
        return BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => 'O+',
            'units_needed' => 1,
            'urgency'      => $urgency,
        ]);
    }

    public function test_matched_donors_carry_a_distance_km_and_travel_category(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => self::HOSPITAL_LAT, 'longitude' => self::HOSPITAL_LNG]);
        // ~2.5km from the hospital coordinates above.
        Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'latitude' => 6.9401, 'longitude' => 79.8712]);

        $bloodRequest = $this->makeRequest($hospital);
        $match = app(DonorMatchingService::class)->findMatches($bloodRequest)->first();

        $this->assertNotNull($match->distance_km);
        $this->assertIsFloat($match->distance_km);
        $this->assertContains($match->travel_category, ['Very Near', 'Near', 'Moderate', 'Far']);
        $this->assertArrayHasKey('distance_km', $match->match_breakdown);
    }

    public function test_closer_donor_scores_higher_when_all_else_is_equal(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => self::HOSPITAL_LAT, 'longitude' => self::HOSPITAL_LNG]);
        $near = Donor::factory()->create([
            'blood_group' => 'O+', 'is_eligible' => true, 'district' => 'Colombo',
            'ai_confidence' => 70, 'response_probability' => 50,
            'latitude' => 6.9301, 'longitude' => 79.8642, // ~0.5km away
        ]);
        $far = Donor::factory()->create([
            'blood_group' => 'O+', 'is_eligible' => true, 'district' => 'Colombo',
            'ai_confidence' => 70, 'response_probability' => 50,
            'latitude' => 7.2906, 'longitude' => 80.6337, // Kandy, ~94km away
        ]);

        $bloodRequest = $this->makeRequest($hospital);
        $matches = app(DonorMatchingService::class)->findMatches($bloodRequest);

        $this->assertEquals($near->id, $matches->first()->id, 'the closer donor should rank first when compatibility/district/AI are identical');
        $this->assertLessThan(
            $matches->firstWhere('id', $far->id)->distance_km,
            $matches->firstWhere('id', $near->id)->distance_km
        );
    }

    public function test_critical_urgency_weighs_distance_more_heavily_than_standard(): void
    {
        $hospital = Hospital::factory()->create(['district' => 'Colombo', 'latitude' => self::HOSPITAL_LAT, 'longitude' => self::HOSPITAL_LNG]);
        // Same district for both donors, so that signal cancels out and
        // only distance + AI profile differ. Near donor has a much weaker
        // AI profile; far donor has a much stronger one -- decisive enough
        // that urgency's distance-weighting shift reliably flips the winner.
        Donor::factory()->create([
            'blood_group' => 'O+', 'is_eligible' => true, 'district' => 'Colombo',
            'ai_confidence' => 30, 'response_probability' => 20,
            'latitude' => 6.9301, 'longitude' => 79.8642, // ~0.5km away
        ]);
        Donor::factory()->create([
            'blood_group' => 'O+', 'is_eligible' => true, 'district' => 'Colombo',
            'ai_confidence' => 95, 'response_probability' => 90,
            'latitude' => 7.2906, 'longitude' => 80.6337, // Kandy, ~94km away
        ]);

        $critical = $this->makeRequest($hospital, 'critical');
        $standard = $this->makeRequest($hospital, 'standard');

        $criticalTop = app(DonorMatchingService::class)->findMatches($critical)->first();
        $standardTop = app(DonorMatchingService::class)->findMatches($standard)->first();

        $this->assertLessThan(5, $criticalTop->distance_km, 'critical urgency should favour the very close donor despite its weak AI profile');
        $this->assertGreaterThan(50, $standardTop->distance_km, 'standard urgency should favour the donor with the much stronger AI profile despite distance');
    }

    public function test_missing_donor_coordinates_fall_back_to_neutral_score_without_crashing(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => self::HOSPITAL_LAT, 'longitude' => self::HOSPITAL_LNG]);
        $donor = Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'latitude' => null, 'longitude' => null]);

        $bloodRequest = $this->makeRequest($hospital);
        $match = app(DonorMatchingService::class)->findMatches($bloodRequest)->first();

        $this->assertEquals($donor->id, $match->id);
        $this->assertNull($match->distance_km);
        $this->assertEquals('Unknown', $match->travel_category);
        $this->assertGreaterThan(0, $match->match_score, 'a donor with no location should still receive a valid, non-zero match score');
    }

    public function test_missing_hospital_coordinates_fall_back_to_neutral_score_without_crashing(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => null, 'longitude' => null]);
        Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'latitude' => 6.9301, 'longitude' => 79.8642]);

        $bloodRequest = $this->makeRequest($hospital);
        $match = app(DonorMatchingService::class)->findMatches($bloodRequest)->first();

        $this->assertNull($match->distance_km);
        $this->assertGreaterThan(0, $match->match_score);
    }

    public function test_a_donor_with_no_location_is_not_penalised_below_a_far_away_donor(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => self::HOSPITAL_LAT, 'longitude' => self::HOSPITAL_LNG]);
        // Same AI profile/district for both -- only location data differs.
        $noLocation = Donor::factory()->create([
            'blood_group' => 'O+', 'is_eligible' => true,
            'ai_confidence' => 70, 'response_probability' => 50,
            'latitude' => null, 'longitude' => null,
        ]);
        $veryFar = Donor::factory()->create([
            'blood_group' => 'O+', 'is_eligible' => true,
            'ai_confidence' => 70, 'response_probability' => 50,
            'latitude' => -33.8688, 'longitude' => 151.2093, // Sydney -- ~9000km away
        ]);

        $bloodRequest = $this->makeRequest($hospital, 'critical');
        $matches = app(DonorMatchingService::class)->findMatches($bloodRequest);

        $noLocationScore = $matches->firstWhere('id', $noLocation->id)->match_score;
        $veryFarScore = $matches->firstWhere('id', $veryFar->id)->match_score;

        $this->assertGreaterThan($veryFarScore, $noLocationScore, 'neutral (unknown) distance should score better than a genuinely huge real distance');
    }

    public function test_nearby_donors_are_ranked_ahead_in_order(): void
    {
        $hospital = Hospital::factory()->create(['district' => 'Colombo', 'latitude' => self::HOSPITAL_LAT, 'longitude' => self::HOSPITAL_LNG]);
        // Identical AI profile and district for all three -- distance is
        // the only thing that can differentiate their ranking.
        $common = ['blood_group' => 'O+', 'is_eligible' => true, 'district' => 'Colombo', 'ai_confidence' => 70, 'response_probability' => 60];
        $closest = Donor::factory()->create($common + ['latitude' => 6.9281, 'longitude' => 79.8622]); // ~0.15km
        $middle  = Donor::factory()->create($common + ['latitude' => 7.0000, 'longitude' => 79.9000]); // ~10km
        $furthest = Donor::factory()->create($common + ['latitude' => 7.2906, 'longitude' => 80.6337]); // ~94km

        $bloodRequest = $this->makeRequest($hospital, 'critical'); // distance-dominant weighting
        $matches = app(DonorMatchingService::class)->findMatches($bloodRequest);

        $order = $matches->pluck('id')->values()->all();
        $this->assertEquals([$closest->id, $middle->id, $furthest->id], $order);
    }
}
