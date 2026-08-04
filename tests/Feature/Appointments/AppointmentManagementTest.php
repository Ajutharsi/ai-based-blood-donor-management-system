<?php

namespace Tests\Feature\Appointments;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\BloodInventory;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentManagementTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(array $overrides = []): Admin
    {
        return Admin::create(array_merge([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ], $overrides));
    }

    public function test_guest_cannot_access_hospital_appointments(): void
    {
        $this->get(route('hospital.appointments.index'))->assertRedirect(route('hospital.login'));
    }

    public function test_hospital_can_view_only_its_own_appointments(): void
    {
        $hospital = Hospital::factory()->create();
        $other = Hospital::factory()->create();
        $donor = Donor::factory()->create(['first_name' => 'Mine', 'last_name' => 'Donor']);
        $otherDonor = Donor::factory()->create(['first_name' => 'Other', 'last_name' => 'Donor']);
        Appointment::factory()->create(['hospital_id' => $hospital->id, 'donor_id' => $donor->id]);
        Appointment::factory()->create(['hospital_id' => $other->id, 'donor_id' => $otherDonor->id]);

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.appointments.index'));

        $response->assertOk();
        $response->assertSee('Mine Donor');
        $response->assertDontSee('Other Donor');
    }

    public function test_hospital_can_approve_a_pending_appointment(): void
    {
        $hospital = Hospital::factory()->create();
        $appointment = Appointment::factory()->status('pending')->create(['hospital_id' => $hospital->id]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.approve', $appointment->id));

        $response->assertRedirect();
        $this->assertSame('approved', $appointment->fresh()->status);
        $this->assertDatabaseHas('notifications', [
            'user_type' => 'donor', 'user_id' => $appointment->donor_id, 'type' => 'appointment_approved',
        ]);
    }

    public function test_hospital_cannot_approve_another_hospitals_appointment(): void
    {
        $owner = Hospital::factory()->create();
        $intruder = Hospital::factory()->create();
        $appointment = Appointment::factory()->status('pending')->create(['hospital_id' => $owner->id]);

        $response = $this->actingAs($intruder, 'hospital')->post(route('hospital.appointments.approve', $appointment->id));

        $response->assertForbidden();
        $this->assertSame('pending', $appointment->fresh()->status);
    }

    public function test_hospital_can_reject_a_pending_appointment_with_reason(): void
    {
        $hospital = Hospital::factory()->create();
        $appointment = Appointment::factory()->status('pending')->create(['hospital_id' => $hospital->id]);

        $response = $this->actingAs($hospital, 'hospital')
            ->post(route('hospital.appointments.reject', $appointment->id), ['reason' => 'No slots available']);

        $response->assertRedirect();
        $this->assertSame('rejected', $appointment->fresh()->status);
        $this->assertDatabaseHas('notifications', [
            'user_type' => 'donor', 'user_id' => $appointment->donor_id, 'type' => 'appointment_rejected',
        ]);
    }

    public function test_only_pending_appointments_can_be_approved_or_rejected(): void
    {
        $hospital = Hospital::factory()->create();
        $appointment = Appointment::factory()->status('approved')->create(['hospital_id' => $hospital->id]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.approve', $appointment->id));

        $response->assertForbidden();
    }

    public function test_hospital_can_reschedule_a_pending_appointment(): void
    {
        $hospital = Hospital::factory()->create();
        $appointment = Appointment::factory()->status('pending')->create(['hospital_id' => $hospital->id]);
        $newDate = now()->addDays(5)->format('Y-m-d');

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.reschedule', $appointment->id), [
            'appointment_date' => $newDate,
            'appointment_time' => '15:30',
        ]);

        $response->assertRedirect();
        $fresh = $appointment->fresh();
        $this->assertSame($newDate, $fresh->appointment_date->format('Y-m-d'));
        $this->assertSame('15:30', $fresh->appointment_time);
        $this->assertDatabaseHas('notifications', [
            'user_type' => 'donor', 'user_id' => $appointment->donor_id, 'type' => 'appointment_rescheduled',
        ]);
    }

    public function test_hospital_can_complete_an_approved_appointment(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O+', 'total_donations' => 2, 'last_donation_date' => now()->subMonths(6)]);
        $appointment = Appointment::factory()->status('approved')->create([
            'hospital_id' => $hospital->id, 'donor_id' => $donor->id,
        ]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.complete', $appointment->id));

        $response->assertRedirect();
        $this->assertSame('completed', $appointment->fresh()->status);
    }

    public function test_completing_an_appointment_records_a_donation(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'O+']);
        $appointment = Appointment::factory()->status('approved')->create([
            'hospital_id' => $hospital->id, 'donor_id' => $donor->id,
        ]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.complete', $appointment->id));

        $this->assertDatabaseHas('donations', [
            'donor_id' => $donor->id, 'blood_group' => 'O+', 'donation_center' => $hospital->name,
        ]);
    }

    public function test_completing_an_appointment_updates_donor_stats(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['total_donations' => 3, 'last_donation_date' => now()->subYear()]);
        $appointment = Appointment::factory()->status('approved')->create([
            'hospital_id' => $hospital->id, 'donor_id' => $donor->id,
            'appointment_date' => now()->format('Y-m-d'),
        ]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.complete', $appointment->id));

        $fresh = $donor->fresh();
        $this->assertSame(4, $fresh->total_donations);
        $this->assertSame(now()->format('Y-m-d'), $fresh->last_donation_date->format('Y-m-d'));
    }

    public function test_completing_an_appointment_increases_hospital_inventory_and_logs_it(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create(['blood_group' => 'B+']);
        $appointment = Appointment::factory()->status('approved')->create([
            'hospital_id' => $hospital->id, 'donor_id' => $donor->id,
        ]);
        $inventory = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('B+')->create(['available_units' => 5]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.complete', $appointment->id));

        $this->assertSame(6, $inventory->fresh()->available_units);
        $this->assertDatabaseHas('blood_inventory_logs', [
            'blood_inventory_id' => $inventory->id, 'units_before' => 5, 'units_after' => 6,
        ]);
    }

    public function test_completing_an_appointment_notifies_all_admins(): void
    {
        $hospital = Hospital::factory()->create();
        $donor = Donor::factory()->create();
        $admin = $this->makeAdmin();
        $appointment = Appointment::factory()->status('approved')->create([
            'hospital_id' => $hospital->id, 'donor_id' => $donor->id,
        ]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.complete', $appointment->id));

        $this->assertDatabaseHas('notifications', [
            'user_type' => 'admin', 'user_id' => $admin->id, 'type' => 'appointment_completed',
        ]);
    }

    public function test_only_approved_appointments_can_be_completed(): void
    {
        $hospital = Hospital::factory()->create();
        $appointment = Appointment::factory()->status('pending')->create(['hospital_id' => $hospital->id]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.appointments.complete', $appointment->id));

        $response->assertForbidden();
        $this->assertDatabaseMissing('donations', ['donor_id' => $appointment->donor_id]);
    }
}
