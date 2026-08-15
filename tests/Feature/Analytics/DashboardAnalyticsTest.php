<?php

namespace Tests\Feature\Analytics;

use App\Models\Admin;
use App\Models\AiPrediction;
use App\Models\Appointment;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DashboardAnalyticsTest extends TestCase
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

    private function fakeAiDown(): void
    {
        Http::fake(function () {
            throw new ConnectionException('Connection refused');
        });
    }

    // ── ADMIN ──

    public function test_admin_dashboard_exposes_all_six_analytics_datasets(): void
    {
        $this->fakeAiDown();
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        foreach (['monthlyDonations', 'bloodRequestTrends', 'bloodInventoryTrends', 'bloodGroupDistribution', 'hospitalPerformance', 'aiPredictionSummary'] as $key) {
            $this->assertNotNull($response->viewData($key), "Missing analytics dataset: {$key}");
        }
    }

    public function test_admin_monthly_donations_counts_this_months_donations(): void
    {
        $this->fakeAiDown();
        $admin = $this->makeAdmin();
        $donor = Donor::factory()->create();
        Donation::factory()->create(['donor_id' => $donor->id, 'donation_date' => now()->format('Y-m-d')]);
        Donation::factory()->create(['donor_id' => $donor->id, 'donation_date' => now()->format('Y-m-d')]);
        Donation::factory()->create(['donor_id' => $donor->id, 'donation_date' => now()->subMonths(3)->format('Y-m-d')]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $thisMonth = collect($response->viewData('monthlyDonations'))->last();
        $this->assertEquals(2, $thisMonth['count']);
    }

    public function test_admin_blood_group_distribution_matches_donor_counts(): void
    {
        $this->fakeAiDown();
        $admin = $this->makeAdmin();
        Donor::factory()->create(['blood_group' => 'O+']);
        Donor::factory()->create(['blood_group' => 'O+']);
        Donor::factory()->create(['blood_group' => 'A-']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $distribution = collect($response->viewData('bloodGroupDistribution'))->keyBy('blood_group');
        $this->assertEquals(2, $distribution['O+']['count']);
        $this->assertEquals(1, $distribution['A-']['count']);
    }

    public function test_admin_hospital_performance_reflects_fulfillment_rate(): void
    {
        $this->fakeAiDown();
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create(['name' => 'Rank Hospital']);
        BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1, 'status' => 'fulfilled']);
        BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1, 'status' => 'pending']);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $performance = collect($response->viewData('hospitalPerformance'))->firstWhere('hospital', 'Rank Hospital');
        $this->assertEquals(2, $performance['total']);
        $this->assertEquals(1, $performance['fulfilled']);
        $this->assertEquals(50, $performance['rate']);
    }

    public function test_admin_ai_prediction_summary_groups_by_type(): void
    {
        $this->fakeAiDown();
        $admin = $this->makeAdmin();
        $donor = Donor::factory()->create();
        AiPrediction::log($donor->id, 'eligibility', [], ['confidence' => 80]);
        AiPrediction::log($donor->id, 'eligibility', [], ['confidence' => 90]);
        AiPrediction::log($donor->id, 'anomaly', [], ['confidence' => 20]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $summary = collect($response->viewData('aiPredictionSummary'))->keyBy('type');
        $this->assertEquals(2, $summary['Eligibility']['total']);
        $this->assertEquals(85.0, $summary['Eligibility']['avg_confidence']);
        $this->assertEquals(1, $summary['Anomaly']['total']);
    }

    // ── HOSPITAL ──

    public function test_hospital_dashboard_exposes_all_four_analytics_datasets(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.dashboard'));

        $response->assertOk();
        foreach (['monthlyRequests', 'donationStatistics', 'inventoryTrends', 'appointmentTrends'] as $key) {
            $this->assertNotNull($response->viewData($key), "Missing analytics dataset: {$key}");
        }
    }

    public function test_hospital_monthly_requests_only_counts_its_own_requests(): void
    {
        $own = Hospital::factory()->create();
        $other = Hospital::factory()->create();
        BloodRequest::create(['hospital_id' => $own->id, 'blood_group' => 'O+', 'units_needed' => 1, 'status' => 'pending']);
        BloodRequest::create(['hospital_id' => $own->id, 'blood_group' => 'O+', 'units_needed' => 1, 'status' => 'pending']);
        BloodRequest::create(['hospital_id' => $other->id, 'blood_group' => 'O+', 'units_needed' => 1, 'status' => 'pending']);

        $response = $this->actingAs($own, 'hospital')->get(route('hospital.dashboard'));

        $thisMonth = collect($response->viewData('monthlyRequests'))->last();
        $this->assertEquals(2, $thisMonth['total']);
    }

    public function test_hospital_donation_statistics_scoped_by_donation_center_name(): void
    {
        $own = Hospital::factory()->create(['name' => 'Stats Hospital']);
        $other = Hospital::factory()->create(['name' => 'Other Stats Hospital']);
        $donor = Donor::factory()->create();
        Donation::factory()->create(['donor_id' => $donor->id, 'donation_center' => $own->name, 'donation_date' => now()->format('Y-m-d')]);
        Donation::factory()->create(['donor_id' => $donor->id, 'donation_center' => $other->name, 'donation_date' => now()->format('Y-m-d')]);

        $response = $this->actingAs($own, 'hospital')->get(route('hospital.dashboard'));

        $thisMonth = collect($response->viewData('donationStatistics'))->last();
        $this->assertEquals(1, $thisMonth['count']);
    }

    public function test_hospital_inventory_trends_scoped_to_own_hospital(): void
    {
        $own = Hospital::factory()->create();
        $other = Hospital::factory()->create();
        $ownItem = BloodInventory::factory()->forHospital($own->id)->bloodGroup('O+')->create(['available_units' => 0]);
        $otherItem = BloodInventory::factory()->forHospital($other->id)->bloodGroup('O+')->create(['available_units' => 0]);

        // Adding units logs an entry with hospital_id stamped on it.
        app(\App\Services\BloodInventoryService::class)->addUnits($ownItem, 5, 'test add');
        app(\App\Services\BloodInventoryService::class)->addUnits($otherItem, 9, 'test add');

        $response = $this->actingAs($own, 'hospital')->get(route('hospital.dashboard'));

        $thisMonth = collect($response->viewData('inventoryTrends'))->last();
        $this->assertEquals(5, $thisMonth['added']);
    }

    public function test_hospital_appointment_trends_scoped_to_own_hospital(): void
    {
        $own = Hospital::factory()->create();
        $other = Hospital::factory()->create();
        Appointment::factory()->status('completed')->create(['hospital_id' => $own->id, 'appointment_date' => now()->format('Y-m-d')]);
        Appointment::factory()->status('completed')->create(['hospital_id' => $other->id, 'appointment_date' => now()->format('Y-m-d')]);

        $response = $this->actingAs($own, 'hospital')->get(route('hospital.dashboard'));

        $thisMonth = collect($response->viewData('appointmentTrends'))->last();
        $this->assertEquals(1, $thisMonth['total']);
        $this->assertEquals(1, $thisMonth['completed']);
    }

    // ── DONOR ──

    public function test_donor_dashboard_exposes_all_three_analytics_datasets(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('donor.dashboard'));

        $response->assertOk();
        foreach (['donationHistoryChart', 'donationTimeline', 'aiPredictionHistory'] as $key) {
            $this->assertNotNull($response->viewData($key), "Missing analytics dataset: {$key}");
        }
    }

    public function test_donor_donation_history_only_includes_own_donations(): void
    {
        $own = Donor::factory()->create();
        $other = Donor::factory()->create();
        Donation::factory()->create(['donor_id' => $own->id, 'donation_date' => now()->format('Y-m-d')]);
        Donation::factory()->create(['donor_id' => $other->id, 'donation_date' => now()->format('Y-m-d')]);

        $response = $this->actingAs($own, 'donor')->get(route('donor.dashboard'));

        $thisMonth = collect($response->viewData('donationHistoryChart'))->last();
        $this->assertEquals(1, $thisMonth['count']);
        $this->assertCount(1, $response->viewData('donationTimeline'));
    }

    public function test_donor_ai_prediction_history_only_includes_own_predictions(): void
    {
        $own = Donor::factory()->create();
        $other = Donor::factory()->create();
        AiPrediction::log($own->id, 'eligibility', [], ['confidence' => 77]);
        AiPrediction::log($other->id, 'eligibility', [], ['confidence' => 33]);

        $response = $this->actingAs($own, 'donor')->get(route('donor.dashboard'));

        $history = $response->viewData('aiPredictionHistory');
        $this->assertCount(1, $history);
        $this->assertEquals(77, $history[0]['confidence']);
    }

    public function test_donor_dashboard_renders_chart_canvases(): void
    {
        $donor = Donor::factory()->create();
        Donation::factory()->create(['donor_id' => $donor->id]);

        $response = $this->actingAs($donor, 'donor')->get(route('donor.dashboard'));

        $response->assertOk();
        $response->assertSee('chartDonationHistory', false);
    }
}
