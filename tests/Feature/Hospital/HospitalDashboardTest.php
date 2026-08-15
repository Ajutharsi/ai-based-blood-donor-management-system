<?php

namespace Tests\Feature\Hospital;

use App\Models\BloodRequest;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_stats_are_scoped_to_the_logged_in_hospital(): void
    {
        $hospital = Hospital::factory()->create();
        $otherHospital = Hospital::factory()->create();

        BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'O+', 'units_needed' => 1, 'status' => 'pending']);
        BloodRequest::create(['hospital_id' => $hospital->id, 'blood_group' => 'A+', 'units_needed' => 1, 'status' => 'fulfilled']);
        BloodRequest::create(['hospital_id' => $otherHospital->id, 'blood_group' => 'B+', 'units_needed' => 1, 'status' => 'pending']);

        $response = $this->actingAs($hospital, 'hospital')->get(route('hospital.dashboard'));

        $response->assertOk();
        $stats = $response->viewData('stats');
        $this->assertEquals(2, $stats['total_requests']);
        $this->assertEquals(1, $stats['pending']);
        $this->assertEquals(1, $stats['fulfilled']);
    }
}
