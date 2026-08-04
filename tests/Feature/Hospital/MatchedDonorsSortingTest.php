<?php

namespace Tests\Feature\Hospital;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchedDonorsSortingTest extends TestCase
{
    use RefreshDatabase;

    private function seedDonorsAndRequest(Hospital $hospital): BloodRequest
    {
        // Deliberately conflicting rankings across the three sort modes:
        // A is closest but weakest AI confidence; C is furthest but
        // strongest AI confidence -- so each sort mode must produce a
        // genuinely different order for the test to mean anything.
        Donor::factory()->create([
            'first_name' => 'DonorA', 'blood_group' => 'O+', 'is_eligible' => true,
            'ai_confidence' => 55, 'response_probability' => 40,
            'latitude' => 6.9281, 'longitude' => 79.8622, // ~0.15km
        ]);
        Donor::factory()->create([
            'first_name' => 'DonorB', 'blood_group' => 'O+', 'is_eligible' => true,
            'ai_confidence' => 75, 'response_probability' => 60,
            'latitude' => 7.0000, 'longitude' => 79.9000, // ~10km
        ]);
        Donor::factory()->create([
            'first_name' => 'DonorC', 'blood_group' => 'O+', 'is_eligible' => true,
            'ai_confidence' => 95, 'response_probability' => 90,
            'latitude' => 7.2906, 'longitude' => 80.6337, // ~94km
        ]);

        return BloodRequest::create([
            'hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1, 'urgency' => 'standard',
        ]);
    }

    public function test_sorting_by_distance_orders_nearest_first(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => 6.9271, 'longitude' => 79.8612]);
        $bloodRequest = $this->seedDonorsAndRequest($hospital);

        $response = $this->actingAs($hospital, 'hospital')
            ->get(route('hospital.requests.show', $bloodRequest) . '?sort=distance');

        $response->assertOk();
        $names = $response->viewData('matched_donors')->pluck('first_name')->values()->all();
        $this->assertEquals(['DonorA', 'DonorB', 'DonorC'], $names);
    }

    public function test_sorting_by_confidence_orders_highest_ai_confidence_first(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => 6.9271, 'longitude' => 79.8612]);
        $bloodRequest = $this->seedDonorsAndRequest($hospital);

        $response = $this->actingAs($hospital, 'hospital')
            ->get(route('hospital.requests.show', $bloodRequest) . '?sort=confidence');

        $response->assertOk();
        $names = $response->viewData('matched_donors')->pluck('first_name')->values()->all();
        $this->assertEquals(['DonorC', 'DonorB', 'DonorA'], $names);
    }

    public function test_default_sort_is_match_score(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => 6.9271, 'longitude' => 79.8612]);
        $bloodRequest = $this->seedDonorsAndRequest($hospital);

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.requests.show', $bloodRequest));

        $response->assertOk();
        $scores = $response->viewData('matched_donors')->pluck('match_score')->values()->all();
        $sorted = $scores;
        rsort($sorted);
        $this->assertEquals($sorted, $scores, 'default view should already be ordered by match_score descending');
    }

    public function test_matched_donors_page_displays_distance_and_travel_category(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => 6.9271, 'longitude' => 79.8612]);
        $bloodRequest = $this->seedDonorsAndRequest($hospital);

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.requests.show', $bloodRequest));

        $response->assertOk();
        $response->assertSee('km ·');
        $content = $response->getContent();
        $hasTravelCategory = str_contains($content, 'Very Near') || str_contains($content, 'Near')
            || str_contains($content, 'Moderate') || str_contains($content, 'Far');
        $this->assertTrue($hasTravelCategory, 'the page should show at least one travel-category label');
    }
}
