<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): Admin
    {
        return Admin::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_dashboard_does_not_crash_when_the_ai_service_is_unreachable(): void
    {
        // Regression test: DashboardController previously called Log::warning()
        // without importing the Log facade inside these catch blocks, which
        // turned a graceful AI-service-down fallback into a fatal 500.
        Http::fake(function () {
            throw new ConnectionException('Connection refused');
        });

        $admin = $this->makeAdmin();
        Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => true]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
    }

    public function test_dashboard_shows_correct_donor_stats(): void
    {
        Http::fake(function () {
            throw new ConnectionException('Connection refused');
        });

        $admin = $this->makeAdmin();
        Donor::factory()->create(['is_eligible' => true]);
        Donor::factory()->create(['is_eligible' => true]);
        Donor::factory()->create(['is_eligible' => false]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $stats = $response->viewData('stats');
        $this->assertEquals(3, $stats['total_donors']);
        $this->assertEquals(2, $stats['eligible_donors']);
        $this->assertEquals(1, $stats['not_eligible']);
    }

    public function test_dashboard_uses_ai_shortage_prediction_when_service_is_available(): void
    {
        Http::fake([
            '*/predict-shortage' => Http::response(['level' => 'critical', 'confidence' => 88]),
            '*/forecast-demand'  => Http::response(['predicted_requests' => 3, 'demand_level' => 'high', 'trend' => 'up']),
            '*/cluster-donors'   => Http::response(['clusters' => [], 'status' => 'ok']),
            '*/model-info'       => Http::response(['model' => 'k-NN', 'accuracy' => 95.63, 'status' => 'success']),
        ]);

        $admin = $this->makeAdmin();
        Donor::factory()->create(['blood_group' => 'O+', 'is_eligible' => false]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $this->assertTrue($response->viewData('aiUsed'));
    }

    public function test_dashboard_shows_real_ai_model_metrics_when_available(): void
    {
        Http::fake([
            '*/predict-shortage' => Http::response(['level' => 'sufficient', 'confidence' => 80]),
            '*/forecast-demand'  => Http::response(['predicted_requests' => 1, 'demand_level' => 'low', 'trend' => 'stable']),
            '*/cluster-donors'   => Http::response(['clusters' => [], 'status' => 'ok']),
            '*/model-info'       => Http::response([
                'model'           => 'k-NN',
                'accuracy'        => 95.63,
                'precision'       => 96.17,
                'recall'          => 99.02,
                'f1_score'        => 97.58,
                'trained_on_rows' => 10000,
                'test_set_rows'   => 3000,
                'trained_at'      => now()->toIso8601String(),
                'status'          => 'success',
            ]),
        ]);

        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $this->assertEquals(95.63, $response->viewData('modelMetrics')['accuracy']);
        $response->assertSee('95.63');
        $response->assertSee('k-NN');
        $response->assertDontSee('94.99');
        $response->assertDontSee('Logistic Regression model');
    }

    public function test_dashboard_shows_honest_fallback_when_model_metrics_unavailable(): void
    {
        Http::fake(function () {
            throw new ConnectionException('Connection refused');
        });

        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $this->assertNull($response->viewData('modelMetrics'));
        $response->assertSee('Not available');
        $response->assertDontSee('94.99');
    }

    public function test_demand_forecast_includes_real_district_breakdown(): void
    {
        Http::fake([
            '*/predict-shortage' => Http::response(['level' => 'sufficient', 'confidence' => 80]),
            '*/forecast-demand'  => Http::response(['predicted_requests' => 2, 'demand_level' => 'medium', 'trend' => 'stable', 'model' => 'Linear Regression (test)']),
            '*/cluster-donors'   => Http::response(['clusters' => [], 'status' => 'ok']),
            '*/model-info'       => Http::response(['status' => 'unavailable']),
        ]);

        $admin   = $this->makeAdmin();
        $colombo = Hospital::factory()->create(['district' => 'Colombo']);
        $kandy   = Hospital::factory()->create(['district' => 'Kandy']);

        // Two requests from Colombo, one from Kandy, all O+, within the forecast window.
        BloodRequest::create(['hospital_id' => $colombo->id, 'blood_group' => 'O+', 'units_needed' => 1]);
        BloodRequest::create(['hospital_id' => $colombo->id, 'blood_group' => 'O+', 'units_needed' => 1]);
        BloodRequest::create(['hospital_id' => $kandy->id, 'blood_group' => 'O+', 'units_needed' => 1]);
        // A different blood group shouldn't leak into O+'s breakdown.
        BloodRequest::create(['hospital_id' => $kandy->id, 'blood_group' => 'A+', 'units_needed' => 1]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $breakdown = $response->viewData('demandForecasts')['O+']['district_breakdown'];

        $this->assertCount(2, $breakdown);
        $this->assertEquals('Colombo', $breakdown[0]['district']);
        $this->assertEquals(2, $breakdown[0]['request_count']);
        $this->assertEquals('Kandy', $breakdown[1]['district']);
        $this->assertEquals(1, $breakdown[1]['request_count']);

        $response->assertSee('Colombo (2)');
        $response->assertSee('Kandy (1)');
    }

    public function test_demand_forecast_model_label_is_not_hardcoded(): void
    {
        Http::fake([
            '*/predict-shortage' => Http::response(['level' => 'sufficient', 'confidence' => 80]),
            '*/forecast-demand'  => Http::response(['predicted_requests' => 1, 'demand_level' => 'low', 'trend' => 'stable', 'model' => 'moving-average (2-week history)']),
            '*/cluster-donors'   => Http::response(['clusters' => [], 'status' => 'ok']),
            '*/model-info'       => Http::response(['status' => 'unavailable']),
        ]);

        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('moving-average (2-week history)');
    }
}
