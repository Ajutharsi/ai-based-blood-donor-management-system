<?php

namespace Tests\Feature\Appointments;

use App\Models\Appointment;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentBookingTest extends TestCase
{
    use RefreshDatabase;

    private function makeRequest(Hospital $hospital, string $bloodGroup = 'O+'): BloodRequest
    {
        return BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => $bloodGroup,
            'units_needed' => 1,
            'urgency'      => 'standard',
            'status'       => 'pending',
        ]);
    }

    private function markAvailable(Donor $donor, BloodRequest $bloodRequest): void
    {
        DonorResponse::create([
            'donor_id'         => $donor->id,
            'blood_request_id' => $bloodRequest->id,
            'status'           => 'available',
            'responded_at'     => now(),
        ]);
    }

    public function test_guest_cannot_access_donor_appointments(): void
    {
        $this->get(route('donor.appointments.index'))->assertRedirect();
    }

    public function test_donor_who_responded_available_can_book_an_appointment(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create();
        $bloodRequest = $this->makeRequest($hospital);
        $this->markAvailable($donor, $bloodRequest);

        $response = $this->actingAs($donor, 'donor')->post(route('donor.appointments.store', $bloodRequest->id), [
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '10:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('appointments', [
            'donor_id'         => $donor->id,
            'blood_request_id' => $bloodRequest->id,
            'hospital_id'      => $hospital->id,
            'status'           => 'pending',
        ]);
    }

    public function test_donor_who_has_not_responded_available_cannot_book(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create();
        $bloodRequest = $this->makeRequest($hospital);

        $response = $this->actingAs($donor, 'donor')->post(route('donor.appointments.store', $bloodRequest->id), [
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '10:00',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('appointments', ['donor_id' => $donor->id]);
    }

    public function test_donor_who_responded_not_available_cannot_book(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create();
        $bloodRequest = $this->makeRequest($hospital);
        DonorResponse::create([
            'donor_id' => $donor->id, 'blood_request_id' => $bloodRequest->id,
            'status' => 'not_available', 'responded_at' => now(),
        ]);

        $response = $this->actingAs($donor, 'donor')->post(route('donor.appointments.store', $bloodRequest->id), [
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '10:00',
        ]);

        $response->assertForbidden();
    }

    public function test_rebooking_the_same_request_updates_instead_of_duplicating(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create();
        $bloodRequest = $this->makeRequest($hospital);
        $this->markAvailable($donor, $bloodRequest);

        $this->actingAs($donor, 'donor')->post(route('donor.appointments.store', $bloodRequest->id), [
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '10:00',
        ]);
        $this->actingAs($donor, 'donor')->post(route('donor.appointments.store', $bloodRequest->id), [
            'appointment_date' => now()->addDays(3)->format('Y-m-d'),
            'appointment_time' => '14:00',
        ]);

        $this->assertSame(1, Appointment::where('donor_id', $donor->id)
            ->where('blood_request_id', $bloodRequest->id)->count());
        $this->assertDatabaseHas('appointments', ['donor_id' => $donor->id, 'appointment_time' => '14:00']);
    }

    public function test_donor_can_view_their_own_appointments_index(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create();
        $bloodRequest = $this->makeRequest($hospital);
        $appointment = Appointment::factory()->create([
            'donor_id' => $donor->id, 'hospital_id' => $hospital->id, 'blood_request_id' => $bloodRequest->id,
        ]);

        $response = $this->actingAs($donor, 'donor')->get(route('donor.appointments.index'));

        $response->assertOk();
        $response->assertSee($hospital->name);
    }

    public function test_donor_can_cancel_a_pending_appointment(): void
    {
        $donor = Donor::factory()->create();
        $appointment = Appointment::factory()->status('pending')->create(['donor_id' => $donor->id]);

        $response = $this->actingAs($donor, 'donor')->post(route('donor.appointments.cancel', $appointment->id));

        $response->assertRedirect();
        $this->assertSame('cancelled', $appointment->fresh()->status);
    }

    public function test_donor_cannot_cancel_another_donors_appointment(): void
    {
        $owner = Donor::factory()->create();
        $intruder = Donor::factory()->create();
        $appointment = Appointment::factory()->status('pending')->create(['donor_id' => $owner->id]);

        $response = $this->actingAs($intruder, 'donor')->post(route('donor.appointments.cancel', $appointment->id));

        $response->assertForbidden();
        $this->assertSame('pending', $appointment->fresh()->status);
    }

    public function test_donor_cannot_cancel_a_completed_appointment(): void
    {
        $donor = Donor::factory()->create();
        $appointment = Appointment::factory()->status('completed')->create(['donor_id' => $donor->id]);

        $response = $this->actingAs($donor, 'donor')->post(route('donor.appointments.cancel', $appointment->id));

        $response->assertForbidden();
        $this->assertSame('completed', $appointment->fresh()->status);
    }

    public function test_booking_notifies_the_hospital(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create();
        $bloodRequest = $this->makeRequest($hospital);
        $this->markAvailable($donor, $bloodRequest);

        $this->actingAs($donor, 'donor')->post(route('donor.appointments.store', $bloodRequest->id), [
            'appointment_date' => now()->addDays(2)->format('Y-m-d'),
            'appointment_time' => '10:00',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_type' => 'hospital', 'user_id' => $hospital->id, 'type' => 'appointment_booked',
        ]);
    }
}
