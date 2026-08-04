<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\AiPrediction;
use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AiPredictionsPageTest extends TestCase
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

    public function test_guest_cannot_access_ai_predictions_page(): void
    {
        $this->get(route('admin.ai-predictions.index'))->assertRedirect();
    }

    public function test_admin_can_view_ai_predictions_list(): void
    {
        $admin = $this->makeAdmin();
        $donor = Donor::factory()->create();

        AiPrediction::create([
            'donor_id'        => $donor->id,
            'prediction_type' => 'eligibility',
            'model'           => 'k-NN',
            'input'           => ['age' => 25],
            'output'          => ['eligible' => true],
            'confidence'      => 91.5,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.ai-predictions.index'));

        $response->assertOk();
        $response->assertSee($donor->full_name);
        $response->assertSee('k-NN');
        $response->assertSee('91.5%');
    }

    public function test_ai_predictions_list_can_be_filtered_by_type(): void
    {
        $admin = $this->makeAdmin();
        $donor = Donor::factory()->create();

        AiPrediction::create([
            'donor_id' => $donor->id, 'prediction_type' => 'eligibility',
            'model' => 'k-NN', 'input' => [], 'output' => [], 'confidence' => 90,
        ]);
        AiPrediction::create([
            'donor_id' => $donor->id, 'prediction_type' => 'anomaly',
            'model' => 'Isolation Forest', 'input' => [], 'output' => [], 'confidence' => 10,
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.ai-predictions.index', ['prediction_type' => 'anomaly']));

        $response->assertOk();
        $response->assertSee('Isolation Forest');
        // "k-NN" itself still appears in the filter dropdown's option list,
        // so check the excluded row's confidence value isn't in the results instead.
        $response->assertDontSee('90.0%');
    }
}
