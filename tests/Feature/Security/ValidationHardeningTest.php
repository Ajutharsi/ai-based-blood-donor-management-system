<?php

namespace Tests\Feature\Security;

use App\Models\Appointment;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_blood_request_rejects_a_blood_group_outside_the_fixed_enum(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.request.store'), [
            'blood_group'  => 'NOT-A-GROUP',
            'units_needed' => 1,
            'urgency'      => 'standard',
        ]);

        $response->assertSessionHasErrors('blood_group');
    }

    public function test_blood_request_rejects_an_overlong_ward_value(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.request.store'), [
            'blood_group'  => 'O+',
            'units_needed' => 1,
            'urgency'      => 'standard',
            'ward'         => str_repeat('a', 101),
        ]);

        $response->assertSessionHasErrors('ward');
    }

    public function test_blood_request_rejects_an_overlong_notes_value(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.request.store'), [
            'blood_group'  => 'O+',
            'units_needed' => 1,
            'urgency'      => 'standard',
            'notes'        => str_repeat('a', 1001),
        ]);

        $response->assertSessionHasErrors('notes');
    }

    public function test_appointment_time_rejects_a_malformed_value(): void
    {
        $donor = Donor::factory()->eligible()->bloodGroup('O+')->create();
        $hospital = Hospital::factory()->create();
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1, 'status' => 'pending']);
        DonorResponse::create(['donor_id' => $donor->id, 'blood_request_id' => $bloodRequest->id, 'status' => 'available', 'responded_at' => now()]);

        $response = $this->actingAs($donor, 'donor')->post(route('donor.appointments.store', $bloodRequest), [
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => 'not-a-time',
        ]);

        $response->assertSessionHasErrors('appointment_time');
    }

    public function test_hospital_reschedule_rejects_a_malformed_appointment_time(): void
    {
        $donor = Donor::factory()->create();
        $hospital = Hospital::factory()->create();
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1, 'status' => 'pending']);
        $appointment = Appointment::factory()->create([
            'donor_id' => $donor->id, 'blood_request_id' => $bloodRequest->id, 'hospital_id' => $hospital->id, 'status' => 'pending',
        ]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.reschedule', $appointment), [
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '25:99',
        ]);

        $response->assertSessionHasErrors('appointment_time');
    }

    public function test_admin_donor_search_with_like_wildcard_characters_does_not_error(): void
    {
        $admin = \App\Models\Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);
        Donor::factory()->create(['first_name' => 'Alice', 'last_name' => 'Smith', 'email' => 'alice@example.com']);

        // LIKE metacharacters submitted as a search term are stripped
        // before being embedded in the query, so a search containing them
        // can't be used to widen the match pattern in unexpected ways.
        $response = $this->actingAs($admin, 'admin')->get(route('admin.donors.index', ['search' => 'Ali%_ce']));

        $response->assertOk();
        $response->assertSee('Alice Smith');
    }
}
