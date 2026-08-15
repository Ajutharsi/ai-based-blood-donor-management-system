<?php

namespace Tests\Feature\Security;

use App\Models\Admin;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_donor_session_id_regenerates_on_login(): void
    {
        $donor = Donor::factory()->create();

        $this->get('/');
        $beforeId = session()->getId();

        $this->post(route('donor.login'), ['email' => $donor->email, 'password' => 'password']);

        $this->assertNotEquals($beforeId, session()->getId());
    }

    public function test_hospital_session_id_regenerates_on_login(): void
    {
        $hospital = Hospital::factory()->create();

        $this->get('/');
        $beforeId = session()->getId();

        $this->post(route('hospital.login'), ['email' => $hospital->email, 'password' => 'password']);

        $this->assertNotEquals($beforeId, session()->getId());
    }

    public function test_admin_session_id_regenerates_on_login(): void
    {
        $admin = Admin::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);

        $this->get('/');
        $beforeId = session()->getId();

        $this->post(route('admin.login'), ['email' => $admin->email, 'password' => 'password']);

        $this->assertNotEquals($beforeId, session()->getId());
    }

    public function test_donor_session_is_invalidated_on_logout(): void
    {
        $donor = Donor::factory()->create();
        $this->actingAs($donor, 'donor')->get(route('donor.dashboard'));
        $beforeId = session()->getId();

        $this->post(route('donor.logout'));

        $this->assertNotEquals($beforeId, session()->getId());
        $this->assertGuest('donor');
    }

    public function test_hospital_remember_me_sets_a_remember_token(): void
    {
        // Regression test for the bug where Hospital\LoginController never
        // read the "remember" checkbox at all, unlike Donor/Admin.
        $hospital = Hospital::factory()->create();
        $this->assertNull($hospital->remember_token);

        $this->post(route('hospital.login'), [
            'email'    => $hospital->email,
            'password' => 'password',
            'remember' => '1',
        ]);

        $this->assertNotNull($hospital->fresh()->remember_token);
    }
}
