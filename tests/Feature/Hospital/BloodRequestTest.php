<?php

namespace Tests\Feature\Hospital;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BloodRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_create_route_redirects_to_dashboard_instead_of_erroring(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.request.create'));

        $response->assertRedirect(route('hospital.dashboard'));
    }

    public function test_hospital_can_submit_a_blood_request_and_see_matched_donors(): void
    {
        $hospital = Hospital::factory()->create();
        Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'ai_confidence' => 90]);
        Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => false, 'ai_confidence' => 95]);
        Donor::factory()->create(['blood_group' => 'A+', 'is_eligible' => true, 'ai_confidence' => 95]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.request.store'), [
            'blood_group'  => 'O+',
            'units_needed' => 2,
            'urgency'      => 'urgent',
            'ward'         => 'ICU',
        ]);

        $response->assertOk();
        $response->assertSee('O+');
        $this->assertDatabaseHas('blood_requests', [
            'hospital_id'  => $hospital->id,
            'blood_group'  => 'O+',
            'units_needed' => 2,
            'urgency'      => 'urgent',
            'status'       => 'pending',
        ]);
    }

    public function test_blood_request_requires_valid_urgency(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.request.store'), [
            'blood_group'  => 'O+',
            'units_needed' => 1,
            'urgency'      => 'not-a-real-level',
        ]);

        $response->assertSessionHasErrors('urgency');
    }

    public function test_hospital_only_sees_its_own_requests_in_index(): void
    {
        $hospitalA = Hospital::factory()->create();
        $hospitalB = Hospital::factory()->create();
        BloodRequest::create(['hospital_id' => $hospitalA->id, 'blood_group' => 'O+', 'units_needed' => 1]);
        BloodRequest::create(['hospital_id' => $hospitalB->id, 'blood_group' => 'A+', 'units_needed' => 1]);

        $response = $this->actingAs($hospitalA, 'hospital')->get(route('hospital.requests.index'));

        $response->assertOk();
        $this->assertEquals(1, $response->viewData('requests')->total());
    }

    public function test_hospital_can_fulfill_its_own_request(): void
    {
        $hospital = Hospital::factory()->create();
        $bloodRequest = BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => 'O+',
            'units_needed' => 1,
        ]);

        $response = $this->actingAs($hospital, 'hospital')
            ->post(route('hospital.requests.fulfill', $bloodRequest));

        $response->assertRedirect();
        $this->assertEquals('fulfilled', $bloodRequest->fresh()->status);
    }

    public function test_hospital_cannot_fulfill_another_hospitals_request(): void
    {
        $owner = Hospital::factory()->create();
        $intruder = Hospital::factory()->create();
        $bloodRequest = BloodRequest::create([
            'hospital_id'  => $owner->id,
            'blood_group'  => 'O+',
            'units_needed' => 1,
        ]);

        $response = $this->actingAs($intruder, 'hospital')
            ->post(route('hospital.requests.fulfill', $bloodRequest));

        $response->assertForbidden();
        $this->assertEquals('pending', $bloodRequest->fresh()->status);
    }
}
