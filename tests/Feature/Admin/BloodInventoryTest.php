<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\BloodInventory;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BloodInventoryTest extends TestCase
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

    public function test_guest_cannot_access_admin_inventory(): void
    {
        $this->get(route('admin.inventory.index'))->assertRedirect(route('admin.login'));
    }

    public function test_donor_guard_cannot_access_admin_inventory(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('admin.inventory.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_hospital_guard_cannot_access_admin_inventory(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('admin.inventory.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_inventory_across_all_hospitals(): void
    {
        $admin = $this->makeAdmin();
        $hospitalA = Hospital::factory()->create(['name' => 'Hospital A']);
        $hospitalB = Hospital::factory()->create(['name' => 'Hospital B']);
        BloodInventory::factory()->forHospital($hospitalA->id)->bloodGroup('O+')->create();
        BloodInventory::factory()->forHospital($hospitalB->id)->bloodGroup('AB-')->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.inventory.index'));

        $response->assertOk();
        $response->assertSee('Hospital A');
        $response->assertSee('Hospital B');
    }

    public function test_admin_can_filter_inventory_by_hospital(): void
    {
        $admin = $this->makeAdmin();
        $hospitalA = Hospital::factory()->create();
        $hospitalB = Hospital::factory()->create();
        BloodInventory::factory()->forHospital($hospitalA->id)->bloodGroup('O+')->create();
        BloodInventory::factory()->forHospital($hospitalB->id)->bloodGroup('AB-')->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.inventory.index', ['hospital_id' => $hospitalA->id]));

        $response->assertOk();
        $response->assertViewHas('inventory', function ($inventory) use ($hospitalA) {
            return $inventory->count() === 1 && $inventory->first()->hospital_id === $hospitalA->id;
        });
    }

    public function test_admin_can_filter_inventory_by_blood_group(): void
    {
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create();
        BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 42]);
        BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('AB-')->create(['available_units' => 17]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.inventory.index', ['blood_group' => 'AB-']));

        $response->assertOk();
        $response->assertViewHas('inventory', function ($inventory) {
            return $inventory->count() === 1 && $inventory->first()->blood_group === 'AB-' && $inventory->first()->available_units === 17;
        });
    }

    public function test_admin_cannot_submit_inventory_mutation_routes(): void
    {
        // Admin\BloodInventoryController only exposes index() -- there is no
        // add/remove/threshold route in the admin. prefix at all, so any
        // attempt to reach a hospital-style mutation URL under /admin
        // simply 404s rather than being reachable and rejected.
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create();

        $response = $this->actingAs($admin, 'admin')->post("/admin/inventory/{$item->id}/add", ['units' => 5]);

        $response->assertNotFound();
    }

    public function test_dashboard_shows_inventory_summary_statistics(): void
    {
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create();
        BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->critical()->create();
        BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('A+')->sufficient()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Blood Inventory');
        $response->assertSee('Critical Blood Groups');
    }

    public function test_dashboard_total_units_reflects_all_hospitals_stock(): void
    {
        $admin = $this->makeAdmin();
        $hospitalA = Hospital::factory()->create();
        $hospitalB = Hospital::factory()->create();
        BloodInventory::factory()->forHospital($hospitalA->id)->bloodGroup('O+')->create(['available_units' => 30]);
        BloodInventory::factory()->forHospital($hospitalB->id)->bloodGroup('A+')->create(['available_units' => 20]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('inventoryTotalUnits', 50);
    }
}
