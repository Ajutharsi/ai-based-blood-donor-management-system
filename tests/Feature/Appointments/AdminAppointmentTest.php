<?php

namespace Tests\Feature\Appointments;

use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAppointmentTest extends TestCase
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

    public function test_guest_cannot_access_admin_appointments(): void
    {
        $this->get(route('admin.appointments.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_appointments_across_all_hospitals(): void
    {
        $admin = $this->makeAdmin();
        $hospitalA = Hospital::factory()->create(['name' => 'Hospital A']);
        $hospitalB = Hospital::factory()->create(['name' => 'Hospital B']);
        Appointment::factory()->create(['hospital_id' => $hospitalA->id]);
        Appointment::factory()->create(['hospital_id' => $hospitalB->id]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.appointments.index'));

        $response->assertOk();
        $response->assertSee('Hospital A');
        $response->assertSee('Hospital B');
    }

    public function test_admin_cannot_approve_an_appointment(): void
    {
        $admin = $this->makeAdmin();
        $appointment = Appointment::factory()->status('pending')->create();

        $response = $this->actingAs($admin, 'admin')->post(route('hospital.appointments.approve', $appointment->id));

        $response->assertRedirect(route('hospital.login'));
        $this->assertSame('pending', $appointment->fresh()->status);
    }

    public function test_donor_guard_cannot_access_admin_appointments(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('admin.appointments.index'));

        $response->assertRedirect(route('admin.login'));
    }
}
