<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(array $overrides = []): Admin
    {
        return Admin::create(array_merge([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password123'),
        ], $overrides));
    }

    public function test_admin_can_log_in_with_valid_credentials(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->post(route('admin.login'), [
            'email'    => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated('admin');
    }

    public function test_admin_login_fails_with_invalid_credentials(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->post(route('admin.login'), [
            'email'    => $admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_admin_can_log_out(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin, 'admin');
        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest('admin');
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_remember_me_sets_a_remember_token(): void
    {
        $admin = $this->makeAdmin();
        $this->assertNull($admin->remember_token);

        $this->post(route('admin.login'), [
            'email'    => $admin->email,
            'password' => 'password123',
            'remember' => '1',
        ]);

        $this->assertNotNull($admin->fresh()->remember_token);
    }

    public function test_donor_cannot_access_admin_protected_routes(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_hospital_cannot_access_admin_protected_routes(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_cannot_access_donor_protected_routes(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('donor.dashboard'));

        $response->assertRedirect(route('donor.login'));
    }

    public function test_admin_cannot_access_hospital_protected_routes(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('hospital.dashboard'));

        $response->assertRedirect(route('hospital.login'));
    }
}
