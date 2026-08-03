<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonorEligibilityToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_toggle_donor_eligibility_without_corrupting_it(): void
    {
        $admin = Admin::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $donor = Donor::factory()->create(['is_eligible' => true]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.donors.toggle', $donor));
        $response->assertRedirect();
        $donor->refresh();
        $this->assertFalse((bool) $donor->is_eligible);

        $this->actingAs($admin, 'admin')->post(route('admin.donors.toggle', $donor));
        $donor->refresh();
        $this->assertTrue((bool) $donor->is_eligible);
    }

    public function test_admin_can_view_donor_show_page_with_donations_and_medical_condition(): void
    {
        $admin = Admin::create([
            'name'     => 'Admin',
            'email'    => 'admin3@example.com',
            'password' => bcrypt('password'),
        ]);
        $donor = Donor::factory()->create(['medical_condition' => 'Asthma']);
        $donor->donations()->create([
            'donation_date'   => now()->subDays(5),
            'blood_group'     => $donor->blood_group,
            'donation_center' => 'NBTS Kandy',
            'units'           => 1,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.donors.show', $donor));

        $response->assertOk();
        $response->assertSee('Asthma');
        $response->assertSee('NBTS Kandy');
    }
}
