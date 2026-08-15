<?php

namespace Tests\Feature\Reports;

use App\Models\Admin;
use App\Models\AiPrediction;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportTest extends TestCase
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

    public function test_guest_cannot_access_admin_reports(): void
    {
        $this->get(route('admin.reports.index'))->assertRedirect(route('admin.login'));
    }

    public function test_guest_cannot_export_admin_reports(): void
    {
        $this->get(route('admin.reports.export', ['type' => 'donors', 'format' => 'pdf']))
            ->assertRedirect(route('admin.login'));
    }

    public function test_hospital_guard_cannot_access_admin_reports(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('admin.reports.index'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_each_report_type(): void
    {
        $admin = $this->makeAdmin();

        foreach (['donors', 'hospitals', 'blood_requests', 'blood_inventory', 'donations', 'ai_predictions'] as $type) {
            $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.index', ['type' => $type]));
            $response->assertOk();
        }
    }

    public function test_donor_report_blood_group_filter_narrows_results(): void
    {
        $admin = $this->makeAdmin();
        Donor::factory()->create(['first_name' => 'Match', 'last_name' => 'Donor', 'blood_group' => 'O+']);
        Donor::factory()->create(['first_name' => 'Other', 'last_name' => 'Donor', 'blood_group' => 'A+']);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.reports.index', ['type' => 'donors', 'blood_group' => 'O+']));

        $response->assertOk();
        $response->assertSee('Match Donor');
        $response->assertDontSee('Other Donor');
    }

    public function test_hospital_report_district_filter_narrows_results(): void
    {
        $admin = $this->makeAdmin();
        Hospital::factory()->create(['name' => 'Colombo Hospital', 'district' => 'Colombo']);
        Hospital::factory()->create(['name' => 'Kandy Hospital', 'district' => 'Kandy']);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.reports.index', ['type' => 'hospitals', 'district' => 'Colombo']));

        $response->assertOk();
        $response->assertSee('Colombo Hospital');
        $response->assertDontSee('Kandy Hospital');
    }

    public function test_blood_request_report_status_filter_narrows_results(): void
    {
        $admin = $this->makeAdmin();
        $hospital = Hospital::factory()->create();
        BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1, 'urgency' => 'standard', 'status' => 'pending', 'ward' => 'PendingWard']);
        BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1, 'urgency' => 'standard', 'status' => 'fulfilled', 'ward' => 'FulfilledWard']);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.reports.index', ['type' => 'blood_requests', 'status' => 'fulfilled']));

        $response->assertOk();
        $response->assertSee('FulfilledWard');
        $response->assertDontSee('PendingWard');
    }

    public function test_blood_inventory_report_status_filter_narrows_results(): void
    {
        // The hospital filter dropdown always lists every hospital
        // regardless of the applied filter, so asserting on hospital name
        // text in the page body would collide with that dropdown. Inspect
        // the controller's own filtered row count instead.
        $admin = $this->makeAdmin();
        $hospitalA = Hospital::factory()->create();
        $hospitalB = Hospital::factory()->create();
        BloodInventory::factory()->forHospital($hospitalA->id)->bloodGroup('O+')->create(['available_units' => 0, 'minimum_threshold' => 10]);
        BloodInventory::factory()->forHospital($hospitalB->id)->bloodGroup('O+')->create(['available_units' => 50, 'minimum_threshold' => 10]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.reports.index', ['type' => 'blood_inventory', 'status' => 'critical']));

        $response->assertOk();
        $response->assertViewHas('totalCount', 1);
    }

    public function test_donation_report_hospital_filter_narrows_results(): void
    {
        $admin = $this->makeAdmin();
        $hospitalA = Hospital::factory()->create(['name' => 'Donation Hospital A']);
        $hospitalB = Hospital::factory()->create(['name' => 'Donation Hospital B']);
        $donorA = Donor::factory()->create(['first_name' => 'DonorA', 'last_name' => 'X']);
        $donorB = Donor::factory()->create(['first_name' => 'DonorB', 'last_name' => 'Y']);
        Donation::factory()->create(['donor_id' => $donorA->id, 'donation_center' => $hospitalA->name]);
        Donation::factory()->create(['donor_id' => $donorB->id, 'donation_center' => $hospitalB->name]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.reports.index', ['type' => 'donations', 'hospital_id' => $hospitalA->id]));

        $response->assertOk();
        $response->assertSee('DonorA X');
        $response->assertDontSee('DonorB Y');
    }

    public function test_ai_prediction_report_type_filter_narrows_results(): void
    {
        $admin = $this->makeAdmin();
        $eligibilityDonor = Donor::factory()->create(['first_name' => 'Eligible', 'last_name' => 'Case']);
        $anomalyDonor = Donor::factory()->create(['first_name' => 'Anomaly', 'last_name' => 'Case']);
        AiPrediction::log($eligibilityDonor->id, 'eligibility', [], ['status' => 'ok']);
        AiPrediction::log($anomalyDonor->id, 'anomaly', [], ['status' => 'ok']);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.reports.index', ['type' => 'ai_predictions', 'status' => 'anomaly']));

        $response->assertOk();
        $response->assertSee('Anomaly Case');
        $response->assertDontSee('Eligible Case');
    }

    public function test_unknown_report_type_falls_back_to_donors(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.index', ['type' => 'not_a_real_type']));

        $response->assertOk();
        $response->assertSee('Donor Report');
    }

    public function test_admin_can_export_every_type_as_pdf(): void
    {
        $admin = $this->makeAdmin();
        Donor::factory()->create();
        Hospital::factory()->create();

        foreach (['donors', 'hospitals', 'blood_requests', 'blood_inventory', 'donations', 'ai_predictions'] as $type) {
            $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.export', ['type' => $type, 'format' => 'pdf']));
            $response->assertOk();
            $this->assertStringContainsString('pdf', $response->headers->get('Content-Type'));
        }
    }

    public function test_admin_can_export_every_type_as_excel(): void
    {
        $admin = $this->makeAdmin();
        Donor::factory()->create();

        foreach (['donors', 'hospitals', 'blood_requests', 'blood_inventory', 'donations', 'ai_predictions'] as $type) {
            $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.export', ['type' => $type, 'format' => 'excel']));
            $response->assertOk();
            $this->assertStringContainsString('spreadsheet', $response->headers->get('Content-Type'));
        }
    }

    public function test_export_rejects_unknown_type(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.export', ['type' => 'bogus', 'format' => 'pdf']));

        $response->assertNotFound();
    }

    public function test_export_rejects_unknown_format(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.export', ['type' => 'donors', 'format' => 'csv']));

        $response->assertNotFound();
    }
}
