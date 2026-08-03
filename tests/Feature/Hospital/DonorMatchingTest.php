<?php

namespace Tests\Feature\Hospital;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use App\Services\DonorMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonorMatchingTest extends TestCase
{
    use RefreshDatabase;

    private function makeRequest(Hospital $hospital, string $bloodGroup, string $urgency = 'standard'): BloodRequest
    {
        return BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => $bloodGroup,
            'units_needed' => 1,
            'urgency'      => $urgency,
        ]);
    }

    public function test_o_negative_donor_is_compatible_with_every_blood_group_request(): void
    {
        $hospital = Hospital::factory()->create();
        Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => true]);

        foreach (['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'] as $bg) {
            $bloodRequest = $this->makeRequest($hospital, $bg);
            $matches = app(DonorMatchingService::class)->findMatches($bloodRequest);

            $this->assertTrue(
                $matches->contains(fn ($d) => $d->blood_group === 'O-'),
                "O- donor should be compatible with a {$bg} request (universal donor)"
            );
        }
    }

    public function test_ab_positive_request_can_be_matched_by_every_blood_group(): void
    {
        $hospital = Hospital::factory()->create();
        foreach (['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'] as $bg) {
            Donor::factory()->create(['blood_group' => $bg, 'is_eligible' => true]);
        }

        $bloodRequest = $this->makeRequest($hospital, 'AB+');
        $matches = app(DonorMatchingService::class)->findMatches($bloodRequest, 20);

        $this->assertEquals(8, $matches->count(), 'AB+ is the universal recipient -- every blood group should be compatible');
    }

    public function test_ab_positive_donor_is_only_compatible_with_ab_positive_requests(): void
    {
        $hospital = Hospital::factory()->create();
        Donor::factory()->create(['blood_group' => 'AB+', 'is_eligible' => true]);

        $incompatibleRequest = $this->makeRequest($hospital, 'A+');
        $matches = app(DonorMatchingService::class)->findMatches($incompatibleRequest);
        $this->assertFalse($matches->contains(fn ($d) => $d->blood_group === 'AB+'));

        $compatibleRequest = $this->makeRequest($hospital, 'AB+');
        $matches2 = app(DonorMatchingService::class)->findMatches($compatibleRequest);
        $this->assertTrue($matches2->contains(fn ($d) => $d->blood_group === 'AB+'));
    }

    public function test_rh_positive_donor_is_not_compatible_with_rh_negative_request(): void
    {
        $hospital = Hospital::factory()->create();
        Donor::factory()->create(['blood_group' => 'A+', 'is_eligible' => true]);

        $bloodRequest = $this->makeRequest($hospital, 'A-');
        $matches = app(DonorMatchingService::class)->findMatches($bloodRequest);

        $this->assertFalse(
            $matches->contains(fn ($d) => $d->blood_group === 'A+'),
            'Rh- recipients cannot receive Rh+ donor blood'
        );
    }

    public function test_incompatible_abo_groups_never_match(): void
    {
        $hospital = Hospital::factory()->create();
        Donor::factory()->create(['blood_group' => 'B+', 'is_eligible' => true]);

        $bloodRequest = $this->makeRequest($hospital, 'A+');
        $matches = app(DonorMatchingService::class)->findMatches($bloodRequest);

        $this->assertFalse($matches->contains(fn ($d) => $d->blood_group === 'B+'));
    }

    public function test_ineligible_donors_are_excluded_even_if_blood_compatible(): void
    {
        $hospital = Hospital::factory()->create();
        Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => false]);

        $bloodRequest = $this->makeRequest($hospital, 'O+');
        $matches = app(DonorMatchingService::class)->findMatches($bloodRequest);

        $this->assertTrue($matches->isEmpty());
    }

    public function test_exact_blood_match_ranks_above_compatible_but_different_group(): void
    {
        $hospital   = Hospital::factory()->create();
        $exact      = Donor::factory()->create(['blood_group' => 'A+', 'is_eligible' => true, 'ai_confidence' => 80, 'response_probability' => 60]);
        $compatible = Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => true, 'ai_confidence' => 80, 'response_probability' => 60]);

        $bloodRequest = $this->makeRequest($hospital, 'A+');
        $matches = app(DonorMatchingService::class)->findMatches($bloodRequest);

        $this->assertEquals(
            $exact->id,
            $matches->first()->id,
            'An exact blood-group match should outrank a compatible-but-different donor when all else is equal'
        );
    }

    public function test_same_district_donor_ranks_above_other_district_when_all_else_equal(): void
    {
        $hospital = Hospital::factory()->create(['district' => 'Colombo']);
        $near = Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'district' => 'Colombo', 'ai_confidence' => 70, 'response_probability' => 50]);
        $far  = Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'district' => 'Kandy', 'ai_confidence' => 70, 'response_probability' => 50]);

        $bloodRequest = $this->makeRequest($hospital, 'O+');
        $matches = app(DonorMatchingService::class)->findMatches($bloodRequest);

        $this->assertEquals($near->id, $matches->first()->id);
    }

    public function test_critical_urgency_weighs_district_more_than_standard_urgency(): void
    {
        $hospital = Hospital::factory()->create(['district' => 'Colombo']);
        // Near donor has a modestly weaker AI profile; far donor has a modestly
        // stronger one -- close enough that urgency's weighting shift actually
        // flips which one wins, rather than one donor dominating regardless.
        Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'district' => 'Colombo', 'ai_confidence' => 65, 'response_probability' => 60]);
        Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'district' => 'Kandy', 'ai_confidence' => 80, 'response_probability' => 75]);

        $critical = $this->makeRequest($hospital, 'O+', 'critical');
        $standard = $this->makeRequest($hospital, 'O+', 'standard');

        $criticalTop = app(DonorMatchingService::class)->findMatches($critical)->first();
        $standardTop = app(DonorMatchingService::class)->findMatches($standard)->first();

        $this->assertEquals('Colombo', $criticalTop->district, 'Critical urgency should weigh proximity heavily enough to favour the near donor');
        $this->assertEquals('Kandy', $standardTop->district, 'Standard urgency should favour the donor with the stronger overall AI profile');
    }

    public function test_matched_donors_view_shows_match_score_and_compatibility_tag(): void
    {
        $hospital = Hospital::factory()->create();
        Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => true]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.request.store'), [
            'blood_group'  => 'A+',
            'units_needed' => 1,
            'urgency'      => 'standard',
        ]);

        $response->assertOk();
        $response->assertSee('Compatible donor');
        $response->assertSee('Match');
    }
}
