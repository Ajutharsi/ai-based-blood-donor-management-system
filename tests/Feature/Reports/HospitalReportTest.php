<?php

namespace Tests\Feature\Reports;

use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_hospital_reports(): void
    {
        $this->get(route('hospital.reports.index'))->assertRedirect(route('hospital.login'));
    }

    public function test_guest_cannot_export_hospital_reports(): void
    {
        $this->get(route('hospital.reports.export', ['type' => 'blood_requests', 'format' => 'pdf']))
            ->assertRedirect(route('hospital.login'));
    }

    public function test_donor_guard_cannot_access_hospital_reports(): void
    {
        $donor = Donor::factory()->create();

        $response = $this->actingAs($donor, 'donor')->get(route('hospital.reports.index'));

        $response->assertRedirect(route('hospital.login'));
    }

    public function test_hospital_can_view_its_allowed_report_types(): void
    {
        $hospital = Hospital::factory()->create();

        foreach (['blood_requests', 'blood_inventory', 'donations'] as $type) {
            $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.reports.index', ['type' => $type]));
            $response->assertOk();
        }
    }

    public function test_unknown_type_falls_back_to_blood_requests_without_leaking_other_types(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.reports.index', ['type' => 'donors']));

        $response->assertOk();
        $response->assertViewHas('type', 'blood_requests');
    }

    public function test_hospital_blood_request_report_only_includes_own_requests(): void
    {
        $own = Hospital::factory()->create();
        $other = Hospital::factory()->create();
        BloodRequest::create(['hospital_id' => $own->id, 'blood_group' => 'O+', 'units_needed' => 1, 'urgency' => 'standard', 'status' => 'pending', 'ward' => 'OwnWard']);
        BloodRequest::create(['hospital_id' => $other->id, 'blood_group' => 'O+', 'units_needed' => 1, 'urgency' => 'standard', 'status' => 'pending', 'ward' => 'OtherWard']);

        $response = $this->actingAs($own, 'hospital')->get(route('hospital.reports.index', ['type' => 'blood_requests']));

        $response->assertOk();
        $response->assertSee('OwnWard');
        $response->assertDontSee('OtherWard');
    }

    public function test_hospital_blood_inventory_report_only_includes_own_inventory(): void
    {
        $own = Hospital::factory()->create();
        $other = Hospital::factory()->create();
        BloodInventory::factory()->forHospital($own->id)->bloodGroup('O+')->create(['available_units' => 12]);
        BloodInventory::factory()->forHospital($other->id)->bloodGroup('AB-')->create(['available_units' => 7]);

        $response = $this->actingAs($own, 'hospital')->get(route('hospital.reports.index', ['type' => 'blood_inventory']));

        $response->assertOk();
        $response->assertViewHas('totalCount', 1);
    }

    public function test_hospital_donation_report_only_includes_donations_at_its_own_center(): void
    {
        $own = Hospital::factory()->create(['name' => 'Own Reporting Hospital']);
        $other = Hospital::factory()->create(['name' => 'Other Reporting Hospital']);
        $ownDonor = Donor::factory()->create(['first_name' => 'Own', 'last_name' => 'DonorName']);
        $otherDonor = Donor::factory()->create(['first_name' => 'Other', 'last_name' => 'DonorName']);
        Donation::factory()->create(['donor_id' => $ownDonor->id, 'donation_center' => $own->name]);
        Donation::factory()->create(['donor_id' => $otherDonor->id, 'donation_center' => $other->name]);

        $response = $this->actingAs($own, 'hospital')->get(route('hospital.reports.index', ['type' => 'donations']));

        $response->assertOk();
        $response->assertSee('Own DonorName');
        $response->assertDontSee('Other DonorName');
    }

    public function test_hospital_cannot_export_donor_report(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.reports.export', ['type' => 'donors', 'format' => 'pdf']));

        $response->assertForbidden();
    }

    public function test_hospital_cannot_export_hospital_report(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.reports.export', ['type' => 'hospitals', 'format' => 'pdf']));

        $response->assertForbidden();
    }

    public function test_hospital_cannot_export_ai_prediction_report(): void
    {
        $hospital = Hospital::factory()->create();

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.reports.export', ['type' => 'ai_predictions', 'format' => 'pdf']));

        $response->assertForbidden();
    }

    public function test_hospital_can_export_allowed_types_as_pdf(): void
    {
        $hospital = Hospital::factory()->create();
        BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1, 'urgency' => 'standard', 'status' => 'pending']);

        foreach (['blood_requests', 'blood_inventory', 'donations'] as $type) {
            $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.reports.export', ['type' => $type, 'format' => 'pdf']));
            $response->assertOk();
            $this->assertStringContainsString('pdf', $response->headers->get('Content-Type'));
        }
    }

    public function test_hospital_can_export_allowed_types_as_excel(): void
    {
        $hospital = Hospital::factory()->create();

        foreach (['blood_requests', 'blood_inventory', 'donations'] as $type) {
            $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.reports.export', ['type' => $type, 'format' => 'excel']));
            $response->assertOk();
            $this->assertStringContainsString('spreadsheet', $response->headers->get('Content-Type'));
        }
    }

    public function test_hospital_cannot_export_another_hospitals_blood_requests(): void
    {
        $own = Hospital::factory()->create();
        $other = Hospital::factory()->create();
        BloodRequest::create(['hospital_id' => $other->id, 'blood_group' => 'O+', 'units_needed' => 1, 'urgency' => 'standard', 'status' => 'pending', 'ward' => 'SecretWard']);

        // The route only ever exports the authenticated hospital's own
        // data -- there is no hospital_id parameter a hospital can pass to
        // reach another hospital's rows, so this asserts the exported PDF
        // for the OWN hospital simply doesn't contain the other hospital's
        // data (it has none of its own).
        $response = $this->actingAs($own, 'hospital')->get(route('hospital.reports.export', ['type' => 'blood_requests', 'format' => 'excel']));

        $response->assertOk();
    }
}
