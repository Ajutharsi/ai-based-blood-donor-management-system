<?php

namespace Tests\Feature\Hospital;

use App\Models\Admin;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_hospital_can_log_in_with_valid_credentials(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->post(route('hospital.login'), [
            'email'    => $hospital->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('hospital.dashboard'));
        $this->assertAuthenticated('hospital');
    }

    public function test_hospital_login_fails_with_invalid_credentials(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->post(route('hospital.login'), [
            'email'    => $hospital->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('hospital');
    }

    public function test_hospital_can_log_out(): void
    {
        $hospital = Hospital::factory()->create();

        $this->actingAs($hospital, 'hospital');
        $response = $this->post(route('hospital.logout'));

        $response->assertRedirect(route('hospital.login'));
        $this->assertGuest('hospital');
    }

    public function test_guest_cannot_access_hospital_dashboard(): void
    {
        $response = $this->get(route('hospital.dashboard'));

        $response->assertRedirect(route('hospital.login'));
    }

    public function test_hospital_cannot_access_admin_protected_routes(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_hospital_cannot_access_donor_protected_routes(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('donor.dashboard'));

        $response->assertRedirect(route('donor.login'));
    }

    public function test_donor_cannot_access_hospital_protected_routes(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('hospital.dashboard'));

        $response->assertRedirect(route('hospital.login'));
    }

    public function test_admin_cannot_access_hospital_protected_routes(): void
    {
        $admin = Admin::create([
            'name'     => 'Admin',
            'email'    => 'admin-guard-test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('hospital.dashboard'));

        $response->assertRedirect(route('hospital.login'));
    }
}
