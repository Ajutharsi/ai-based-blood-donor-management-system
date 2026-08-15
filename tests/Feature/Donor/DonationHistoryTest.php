<?php

namespace Tests\Feature\Donor;

use App\Models\Admin;
use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_empty_state_when_donor_has_no_recorded_donations(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('donor.dashboard'));

        $response->assertOk();
        $response->assertSee('No recorded donations yet');
    }

    public function test_admin_can_record_a_donation_for_a_donor(): void
    {
        $admin = Admin::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $donor = Donor::factory()->create(['total_donations' => 2, 'last_donation_date' => null]);

        $response = $this->actingAs($admin, 'admin')->post(
            route('admin.donors.donations.store', $donor),
            ['donation_date' => now()->format('Y-m-d'), 'units' => 1]
        );

        $response->assertRedirect();
        $donor->refresh();

        $this->assertEquals(3, $donor->total_donations);
        $this->assertEquals(now()->format('Y-m-d'), $donor->last_donation_date->format('Y-m-d'));
        $this->assertDatabaseHas('donations', ['donor_id' => $donor->id]);
    }

    public function test_recording_a_donation_requires_a_valid_date(): void
    {
        $admin = Admin::create([
            'name'     => 'Admin',
            'email'    => 'admin2@example.com',
            'password' => bcrypt('password'),
        ]);
        $donor = Donor::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post(
            route('admin.donors.donations.store', $donor),
            ['donation_date' => now()->addDay()->format('Y-m-d')]
        );

        $response->assertSessionHasErrors('donation_date');
        $this->assertDatabaseMissing('donations', ['donor_id' => $donor->id]);
    }

    public function test_recorded_donation_appears_in_donor_dashboard(): void
    {
        $donor = Donor::factory()->create();
        $donor->donations()->create([
            'donation_date'   => now()->subDays(10),
            'blood_group'     => $donor->blood_group,
            'donation_center' => 'NBTS Colombo',
            'units'           => 1,
        ]);

        $response = $this->actingAs($donor, 'donor')->get(route('donor.dashboard'));

        $response->assertOk();
        $response->assertSee('NBTS Colombo');
    }
}
