<?php

namespace Tests\Feature\Donor;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BloodRequestResponseTest extends TestCase
{
    use RefreshDatabase;

    private function makeRequest(Hospital $hospital, string $bloodGroup, string $status = 'pending', ?string $ward = null): BloodRequest
    {
        return BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => $bloodGroup,
            'units_needed' => 1,
            'urgency'      => 'standard',
            'status'       => $status,
            'ward'         => $ward,
        ]);
    }

    public function test_guest_cannot_access_donor_requests_page(): void
    {
        $this->get(route('donor.requests.index'))->assertRedirect();
    }

    public function test_donor_sees_only_compatible_pending_requests(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'A+', 'is_eligible' => true]);

        $compatible = $this->makeRequest($hospital, 'A+', 'pending', 'Ward-Compatible');
        $incompatible = $this->makeRequest($hospital, 'B+', 'pending', 'Ward-Incompatible');
        $fulfilled = $this->makeRequest($hospital, 'A+', 'fulfilled', 'Ward-Fulfilled');

        $response = $this->actingAs($donor, 'donor')->get(route('donor.requests.index'));

        $response->assertOk();
        $response->assertSee('Ward-Compatible');
        $response->assertDontSee('Ward-Incompatible');
        $response->assertDontSee('Ward-Fulfilled');
    }

    public function test_ineligible_donor_sees_no_requests(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => false]);
        $this->makeRequest($hospital, 'O-');

        $response = $this->actingAs($donor, 'donor')->get(route('donor.requests.index'));

        $response->assertOk();
        $response->assertSee('not eligible', false);
    }

    public function test_donor_can_mark_available_for_a_compatible_request(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => true]);
        $bloodRequest = $this->makeRequest($hospital, 'A+'); // O- is compatible with A+

        $response = $this->actingAs($donor, 'donor')
            ->post(route('donor.requests.respond', $bloodRequest->id), ['status' => 'available']);

        $response->assertRedirect();
        $this->assertDatabaseHas('donor_responses', [
            'donor_id'         => $donor->id,
            'blood_request_id' => $bloodRequest->id,
            'status'           => 'available',
        ]);
    }

    public function test_responding_again_updates_the_existing_response_instead_of_duplicating(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => true]);
        $bloodRequest = $this->makeRequest($hospital, 'O-');

        $this->actingAs($donor, 'donor')
            ->post(route('donor.requests.respond', $bloodRequest->id), ['status' => 'available']);
        $this->actingAs($donor, 'donor')
            ->post(route('donor.requests.respond', $bloodRequest->id), ['status' => 'not_available']);

        $this->assertSame(1, DonorResponse::where('donor_id', $donor->id)
            ->where('blood_request_id', $bloodRequest->id)->count());
        $this->assertDatabaseHas('donor_responses', [
            'donor_id'         => $donor->id,
            'blood_request_id' => $bloodRequest->id,
            'status'           => 'not_available',
        ]);
    }

    public function test_donor_cannot_respond_to_an_incompatible_request(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'AB+', 'is_eligible' => true]);
        $bloodRequest = $this->makeRequest($hospital, 'O-'); // AB+ cannot donate to an O- request

        $response = $this->actingAs($donor, 'donor')
            ->post(route('donor.requests.respond', $bloodRequest->id), ['status' => 'available']);

        $response->assertForbidden();
        $this->assertDatabaseMissing('donor_responses', ['donor_id' => $donor->id]);
    }

    public function test_ineligible_donor_cannot_respond(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => false]);
        $bloodRequest = $this->makeRequest($hospital, 'O-');

        $response = $this->actingAs($donor, 'donor')
            ->post(route('donor.requests.respond', $bloodRequest->id), ['status' => 'available']);

        $response->assertForbidden();
    }

    public function test_hospital_matched_donors_view_shows_donor_response_status(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O-', 'is_eligible' => true]);

        $bloodRequest = $this->makeRequest($hospital, 'O-');
        DonorResponse::create([
            'donor_id'         => $donor->id,
            'blood_request_id' => $bloodRequest->id,
            'status'           => 'available',
            'responded_at'     => now(),
        ]);

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.requests.show', $bloodRequest->id));

        $response->assertOk();
        $response->assertSee('Confirmed available');
    }

    public function test_hospital_cannot_view_another_hospitals_request_matches(): void
    {
        $ownHospital = Hospital::factory()->create();
        $otherHospital = Hospital::factory()->create();
        $bloodRequest = $this->makeRequest($otherHospital, 'O-');

        $response = $this->actingAs($ownHospital, 'hospital')->get(route('hospital.requests.show', $bloodRequest->id));

        $response->assertForbidden();
    }
}
