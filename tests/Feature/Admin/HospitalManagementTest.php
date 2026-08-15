<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\BloodRequest;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalManagementTest extends TestCase
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

    public function test_guest_cannot_access_admin_hospital_routes(): void
    {
        $hospital = Hospital::factory()->create();

        $this->get(route('admin.hospitals.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.hospitals.show', $hospital))->assertRedirect(route('admin.login'));
    }

    public function test_hospital_guard_cannot_access_admin_hospital_routes(): void
    {
        $actor = Hospital::factory()->create();
        $target = Hospital::factory()->create();

        $response = $this->actingAs($actor, 'hospital')->get(route('admin.hospitals.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_list_hospitals_with_request_counts(): void
    {
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create(['name' => 'Test General Hospital']);
        BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1]);
        BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'A+', 'units_needed' => 1]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.hospitals.index'));

        $response->assertOk();
        $response->assertSee('Test General Hospital');
        $response->assertSee('2'); // blood_requests_count
    }

    public function test_admin_can_filter_hospitals_by_verification_status(): void
    {
        $admin = $this->makeAdmin();
        Hospital::factory()->create(['name' => 'Verified Hospital', 'is_verified' => true]);
        Hospital::factory()->create(['name' => 'Pending Hospital', 'is_verified' => false]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.hospitals.index', ['status' => 'pending']));

        $response->assertOk();
        $response->assertSee('Pending Hospital');
        $response->assertDontSee('Verified Hospital');
    }

    public function test_admin_can_view_hospital_show_page_with_request_history(): void
    {
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create();
        BloodRequest::create([
            'hospital_id'  => $hospital->id,
            'blood_group'  => 'B+',
            'units_needed' => 3,
            'urgency'      => 'critical',
            'status'       => 'fulfilled',
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.hospitals.show', $hospital));

        $response->assertOk();
        $response->assertSee('B+');
        $response->assertSee('Critical');
        $response->assertSee('Fulfilled');
    }

    public function test_admin_can_toggle_hospital_verification(): void
    {
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create(['is_verified' => false]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.hospitals.toggle', $hospital));
        $response->assertRedirect();
        $this->assertTrue((bool) $hospital->fresh()->is_verified);

        $this->actingAs($admin, 'admin')->post(route('admin.hospitals.toggle', $hospital));
        $this->assertFalse((bool) $hospital->fresh()->is_verified);
    }

    public function test_admin_can_delete_a_hospital(): void
    {
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.hospitals.destroy', $hospital));

        $response->assertRedirect(route('admin.hospitals.index'));
        $this->assertDatabaseMissing('hospitals', ['id' => $hospital->id]);
    }

    public function test_deleting_a_hospital_also_deletes_its_blood_requests(): void
    {
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create();
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1]);

        $this->actingAs($admin, 'admin')->delete(route('admin.hospitals.destroy', $hospital));

        $this->assertDatabaseMissing('blood_requests', ['id' => $bloodRequest->id]);
    }
}
