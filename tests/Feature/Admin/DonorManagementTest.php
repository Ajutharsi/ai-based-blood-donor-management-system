<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonorManagementTest extends TestCase
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

    public function test_guest_cannot_access_admin_donors_index(): void
    {
        $this->get(route('admin.donors.index'))->assertRedirect(route('admin.login'));
    }

    public function test_donor_guard_cannot_access_admin_donors_index(): void
    {
        $actor = Donor::factory()->create();

        $response = $this->actingAs($actor, 'donor')->get(route('admin.donors.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_list_donors(): void
    {
        $admin = $this->makeAdmin();
        Donor::factory()->create(['first_name' => 'Nadia', 'last_name' => 'Perera']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.donors.index'));

        $response->assertOk();
        $response->assertSee('Nadia Perera');
    }

    public function test_admin_can_filter_donors_by_eligibility_status(): void
    {
        $admin = $this->makeAdmin();
        Donor::factory()->create(['first_name' => 'Eligible', 'last_name' => 'Donor', 'is_eligible' => true]);
        Donor::factory()->create(['first_name' => 'Ineligible', 'last_name' => 'Donor', 'is_eligible' => false]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.donors.index', ['status' => 'eligible']));

        $response->assertOk();
        $response->assertSee('Eligible Donor');
        $response->assertDontSee('Ineligible Donor');
    }

    public function test_admin_can_filter_donors_by_blood_group(): void
    {
        $admin = $this->makeAdmin();
        Donor::factory()->create(['first_name' => 'Oplus', 'last_name' => 'Donor', 'blood_group' => 'O+']);
        Donor::factory()->create(['first_name' => 'Abneg', 'last_name' => 'Donor', 'blood_group' => 'AB-']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.donors.index', ['blood_group' => 'AB-']));

        $response->assertOk();
        $response->assertSee('Abneg Donor');
        $response->assertDontSee('Oplus Donor');
    }
}
