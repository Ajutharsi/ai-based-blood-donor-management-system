<?php

namespace Tests\Feature\Notifications;

use App\Models\Admin;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BloodInventoryNotificationTest extends TestCase
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

    public function test_dropping_below_threshold_notifies_the_hospital_of_low_stock(): void
    {
        $hospital = Hospital::factory()->create();
        // 15 units, threshold 10 -> still sufficient until it drops under 10.
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 15, 'minimum_threshold' => 10]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.remove', $item->id), ['units' => 8]);

        $this->assertEquals(1, Notification::where('user_type', 'hospital')
            ->where('user_id', $hospital->id)->where('type', 'inventory_low')->count());
    }

    public function test_dropping_to_critical_notifies_both_the_hospital_and_every_admin(): void
    {
        $admin1 = $this->makeAdmin(['email' => 'a1@example.com']);
        $admin2 = $this->makeAdmin(['email' => 'a2@example.com']);
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 15, 'minimum_threshold' => 10]);

        // Drops straight to 1 unit -- well within the critical band (<= threshold/2).
        $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.remove', $item->id), ['units' => 14]);

        $this->assertEquals(1, Notification::where('user_type', 'hospital')
            ->where('user_id', $hospital->id)->where('type', 'inventory_critical')->count());
        $this->assertEquals(1, Notification::where('user_type', 'admin')
            ->where('user_id', $admin1->id)->where('type', 'inventory_critical')->count());
        $this->assertEquals(1, Notification::where('user_type', 'admin')
            ->where('user_id', $admin2->id)->where('type', 'inventory_critical')->count());
    }

    public function test_replenishing_stock_notifies_the_hospital(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 3, 'minimum_threshold' => 10]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.add', $item->id), ['units' => 20]);

        $this->assertEquals(1, Notification::where('user_type', 'hospital')
            ->where('user_id', $hospital->id)->where('type', 'inventory_replenished')->count());
    }

    public function test_repeated_updates_while_still_low_do_not_spam_notifications(): void
    {
        $hospital = Hospital::factory()->create();
        $item = BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 9, 'minimum_threshold' => 10]);

        // Already low_stock; adding 1 unit keeps it at 10 units == threshold,
        // which is "sufficient" -- so this SHOULD flip status and notify once.
        $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.add', $item->id), ['units' => 1]);
        // Now genuinely sufficient; removing 1 unit brings it back to 9 (low again).
        $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.remove', $item->id), ['units' => 1]);
        // Still low_stock at 8 -- no new transition, should NOT notify again.
        $this->actingAs($hospital, 'hospital')->post(route('hospital.inventory.remove', $item->id), ['units' => 1]);

        // low_stock fired once initially (9<10) is not counted since it started there;
        // the only real transitions are: low->sufficient (at 10), sufficient->low (at 9).
        $this->assertEquals(1, Notification::where('type', 'inventory_low')->count());
        $this->assertEquals(1, Notification::where('type', 'inventory_replenished')->count());
    }

    public function test_fulfilling_a_request_deducts_matching_inventory(): void
    {
        $hospital = Hospital::factory()->create();
        BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 20, 'minimum_threshold' => 10]);
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 5]);

        $this->actingAs($hospital, 'hospital')->post(route('hospital.requests.fulfill', $bloodRequest));

        $inventory = BloodInventory::where('hospital_id', $hospital->id)->where('blood_group', 'O+')->first();
        $this->assertEquals(15, $inventory->available_units);
        $this->assertDatabaseHas('blood_inventory_logs', [
            'blood_inventory_id' => $inventory->id, 'action' => 'fulfillment', 'units_before' => 20, 'units_after' => 15,
        ]);
    }

    public function test_fulfilling_with_insufficient_stock_clamps_at_zero_and_warns(): void
    {
        $hospital = Hospital::factory()->create();
        BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create(['available_units' => 3, 'minimum_threshold' => 10]);
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 8]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.requests.fulfill', $bloodRequest));

        $response->assertSessionHas('warning');
        $inventory = BloodInventory::where('hospital_id', $hospital->id)->where('blood_group', 'O+')->first();
        $this->assertEquals(0, $inventory->available_units);
    }

    public function test_fulfilling_still_succeeds_when_no_inventory_is_tracked_for_that_group(): void
    {
        $hospital = Hospital::factory()->create();
        // No BloodInventory row created for this hospital/blood group at all.
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'AB-', 'units_needed' => 2]);

        $response = $this->actingAs($hospital, 'hospital')->post(route('hospital.requests.fulfill', $bloodRequest));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertEquals('fulfilled', $bloodRequest->fresh()->status);
    }

    public function test_fulfillment_deduction_only_affects_the_matching_hospital_and_blood_group(): void
    {
        $hospitalA = Hospital::factory()->create();
        $hospitalB = Hospital::factory()->create();
        $itemA = BloodInventory::factory()->forHospital($hospitalA->id)->bloodGroup('O+')->create(['available_units' => 20]);
        $itemB = BloodInventory::factory()->forHospital($hospitalB->id)->bloodGroup('O+')->create(['available_units' => 20]);
        $otherGroupA = BloodInventory::factory()->forHospital($hospitalA->id)->bloodGroup('A+')->create(['available_units' => 20]);
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospitalA->id, 'blood_group' => 'O+', 'units_needed' => 5]);

        $this->actingAs($hospitalA, 'hospital')->post(route('hospital.requests.fulfill', $bloodRequest));

        $this->assertEquals(15, $itemA->fresh()->available_units);
        $this->assertEquals(20, $itemB->fresh()->available_units, "another hospital's stock must be untouched");
        $this->assertEquals(20, $otherGroupA->fresh()->available_units, "a different blood group must be untouched");
    }
}
