<?php

namespace Tests\Feature\Hospital;

use App\Models\BloodInventory;
use App\Models\BloodInventoryLog;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BloodInventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_hospital_inventory(): void
    {
        $this->get(route('hospital.inventory.index'))->assertRedirect(route('hospital.login'));
    }

    public function test_donor_guard_cannot_access_hospital_inventory(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('hospital.inventory.index'));

        $response->assertRedirect(route('hospital.login'));
    }

    public function test_visiting_inventory_auto_provisions_all_eight_blood_groups(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.inventory.index'));

        $response->assertOk();
        $this->assertEquals(8, BloodInventory::where('hospital_id', $hospital->id)->count());
        foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group) {
            $this->assertDatabaseHas('blood_inventory', ['hospital_id' => $hospital->id, 'blood_group' => $group]);
        }
    }

    public function test_visiting_inventory_twice_does_not_create_duplicate_rows(): void
    {
        $hospital = Hospital::factory()->create();

        $this->actingAs($hospital, 'hospital')->get(route('hospital.inventory.index'));
        $this->actingAs($hospital, 'hospital')->get(route('hospital.inventory.index'));

        $this->assertEquals(8, BloodInventory::where('hospital_id', $hospital->id)->count());
    }

    public function test_database_rejects_a_duplicate_hospital_blood_group_pair(): void
    {
        $hospital = Hospital::factory()->create();
        BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create();

        $this->expectException(\Illuminate\Database\QueryException::class);

        BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create();
    }

    public function test_hospital_can_add_units(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 10]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.add', $item->id), ['units' => 5]);

        $response->assertRedirect();
        $this->assertEquals(15, $item->fresh()->available_units);
        $this->assertDatabaseHas('blood_inventory_logs', [
            'blood_inventory_id' => $item->id, 'action' => 'add', 'units_before' => 10, 'units_after' => 15,
        ]);
    }

    public function test_hospital_can_remove_units(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 10]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.remove', $item->id), ['units' => 4]);

        $response->assertRedirect();
        $this->assertEquals(6, $item->fresh()->available_units);
    }

    public function test_removing_more_units_than_available_clamps_at_zero_with_warning(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 3]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.remove', $item->id), ['units' => 10]);

        $response->assertRedirect();
        $response->assertSessionHas('warning');
        $this->assertEquals(0, $item->fresh()->available_units);
    }

    public function test_available_units_never_goes_negative(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 0]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.remove', $item->id), ['units' => 5]);

        $this->assertEquals(0, $item->fresh()->available_units);
        $this->assertGreaterThanOrEqual(0, $item->fresh()->available_units);
    }

    public function test_hospital_can_update_minimum_threshold(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['minimum_threshold' => 10]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.threshold', $item->id), ['minimum_threshold' => 25]);

        $response->assertRedirect();
        $this->assertEquals(25, $item->fresh()->minimum_threshold);
    }

    public function test_hospital_cannot_manage_another_hospitals_inventory(): void
    {
        $owner = Hospital::factory()->create();
        $intruder = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($owner->id)->bloodGroup('O+')->create(['available_units' => 10]);

        $response = $this->actingAs($intruder, 'hospital')->post(route('hospital.inventory.add', $item->id), ['units' => 5]);

        $response->assertForbidden();
        $this->assertEquals(10, $item->fresh()->available_units);
    }

    public function test_add_units_validates_input(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create();

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.add', $item->id), ['units' => 0]);

        $response->assertSessionHasErrors('units');
    }

    public function test_hospital_can_view_inventory_history(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 10]);
        $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.add', $item->id), ['units' => 5]);

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.inventory.history'));

        $response->assertOk();
        $response->assertSee('O+');
    }

    public function test_history_only_shows_the_authenticated_hospitals_logs(): void
    {
        $hospitalA = Hospital::factory()->create();
        $hospitalB = Hospital::factory()->create();
        $itemA = BloodInventory::factory()->forHospital($hospitalA->id)->bloodGroup('O+')->create();
        $itemB = BloodInventory::factory()->forHospital($hospitalB->id)->bloodGroup('AB-')->create();
        BloodInventoryLog::create(['blood_inventory_id' => $itemA->id, 'hospital_id' => $hospitalA->id, 'blood_group' => 'O+', 'action' => 'add', 'units_before' => 0, 'units_after' => 5]);
        BloodInventoryLog::create(['blood_inventory_id' => $itemB->id, 'hospital_id' => $hospitalB->id, 'blood_group' => 'AB-', 'action' => 'add', 'units_before' => 0, 'units_after' => 5]);

        $response = $this->actingAs($hospitalA, 'hospital')->get(route('hospital.inventory.history'));

        $response->assertOk();
        $response->assertSee('O+');
        $response->assertDontSee('AB-');
    }
}
