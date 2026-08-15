<?php

namespace Tests\Feature\Location;

use App\Models\Admin;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): Admin
    {
        return Admin::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_only_the_owning_hospital_can_view_matched_donor_distances(): void
    {
        $owner = Hospital::factory()->create(['latitude' => 6.9271, 'longitude' => 79.8612]);
        $intruder = Hospital::factory()->create();
        Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true, 'latitude' => 6.93, 'longitude' => 79.86]);
        $bloodRequest = BloodRequest::create(['hospital_id' => $owner->id, 'blood_group' => 'O+', 'units_needed' => 1]);

        $response = $this->actingAs($intruder, 'hospital')->get(route('hospital.requests.show', $bloodRequest));

        $response->assertForbidden();
    }

    public function test_guest_cannot_view_matched_donor_distances(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => 6.9271, 'longitude' => 79.8612]);
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1]);

        $this->get(route('hospital.requests.show', $bloodRequest))->assertRedirect(route('hospital.login'));
    }

    public function test_donor_guard_cannot_reach_the_matched_donors_page(): void
    {
        $hospital = Hospital::factory()->create(['latitude' => 6.9271, 'longitude' => 79.8612]);
        $donor = Donor::factory()->create();
        $bloodRequest = BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1]);

        $response = $this->actingAs($donor, 'donor')->get(route('hospital.requests.show', $bloodRequest));

        $response->assertRedirect(route('hospital.login'));
    }

    public function test_public_find_donors_page_never_exposes_coordinates(): void
    {
        Donor::factory()->create(['is_eligible' => true, 'latitude' => 6.9271, 'longitude' => 79.8612]);

        $response = $this->get(route('find-donors'));

        $response->assertOk();
        $response->assertDontSee('79.8612');
        $response->assertDontSee('6.9271');
    }

    public function test_donor_cannot_see_other_donors_coordinates_anywhere_in_their_own_pages(): void
    {
        $donor = Donor::factory()->create();
        Donor::factory()->create(['latitude' => 6.9271, 'longitude' => 79.8612]);

        $dashboard = $this->actingAs($donor, 'donor')->get(route('donor.dashboard'));
        $requests = $this->actingAs($donor, 'donor')->get(route('donor.requests.index'));

        $dashboard->assertDontSee('79.8612');
        $requests->assertDontSee('79.8612');
    }

    public function test_admin_dashboard_shows_aggregate_counts_not_individual_donor_coordinates(): void
    {
        $admin = $this->makeAdmin();
        Donor::factory()->create(['is_eligible' => true, 'latitude' => 6.9271, 'longitude' => 79.8612]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Donor Geographic Distribution');
        $response->assertSee('Donors With Location Set');
        // The exact coordinate value must never leak into the admin view --
        // only counts/averages/district breakdowns are admin-visible.
        $response->assertDontSee('6.9271');
        $response->assertDontSee('79.8612');
    }

    public function test_admin_can_view_aggregated_inventory_and_geo_stats_but_not_individual_donor_location(): void
    {
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create(['latitude' => 6.9271, 'longitude' => 79.8612]);
        BloodInventory::factory()->forHospital($hospital->id)->bloodGroup('O+')->create();
        Donor::factory()->create(['is_eligible' => true, 'latitude' => 6.93, 'longitude' => 79.87]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertViewHas('geoDistribution');
        $response->assertViewHas('averageDonorDistanceToHospitals');
        // Hospital markers (institutional, not personal) are fine to expose;
        // donor markers deliberately are not part of the view data at all.
        $response->assertViewMissing('donorMapMarkers');
    }
}
