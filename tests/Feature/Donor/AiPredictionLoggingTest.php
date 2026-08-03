<?php

namespace Tests\Feature\Donor;

use App\Models\AiPrediction;
use App\Models\Donor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiPredictionLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Http::fake([
            '*/predict'          => Http::response(['eligible' => true, 'confidence' => 92.5, 'model' => 'k-NN', 'status' => 'success']),
            '*/predict-response' => Http::response(['response_probability' => 70, 'level' => 'high', 'model' => 'Random Forest', 'status' => 'success']),
            '*/detect-anomaly'   => Http::response(['is_anomaly' => false, 'anomaly_score' => 0.1, 'model' => 'Isolation Forest', 'status' => 'success']),
        ]);
    }

    public function test_registration_logs_all_three_ai_predictions(): void
    {
        $response = $this->post(route('donor.register'), [
            'first_name'            => 'Kasun',
            'last_name'             => 'Perera',
            'email'                 => 'kasun@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'blood_group'           => 'O+',
            'weight_kg'             => 65,
            'hemoglobin'            => 13.5,
        ]);

        $response->assertRedirect(route('donor.dashboard'));
        $donor = Donor::where('email', 'kasun@example.com')->first();

        $this->assertEquals(3, AiPrediction::where('donor_id', $donor->id)->count());

        $eligibility = AiPrediction::where('donor_id', $donor->id)->where('prediction_type', 'eligibility')->first();
        $this->assertEquals('k-NN', $eligibility->model);
        $this->assertEquals(92.5, $eligibility->confidence);
        $this->assertEquals('O+', $eligibility->input['blood_group']);
        $this->assertTrue($eligibility->output['eligible']);

        $response_pred = AiPrediction::where('donor_id', $donor->id)->where('prediction_type', 'response')->first();
        $this->assertEquals('Random Forest', $response_pred->model);
        $this->assertEquals(70, $response_pred->confidence);

        $anomaly = AiPrediction::where('donor_id', $donor->id)->where('prediction_type', 'anomaly')->first();
        $this->assertEquals('Isolation Forest', $anomaly->model);
        $this->assertEquals(0.1, $anomaly->confidence);
    }

    public function test_profile_update_logs_an_eligibility_prediction(): void
    {
        $donor = Donor::factory()->create();

        $this->actingAs($donor, 'donor')->put(route('donor.profile.update'), [
            'first_name'  => $donor->first_name,
            'last_name'   => $donor->last_name,
            'email'       => $donor->email,
            'weight_kg'   => 70,
            'hemoglobin'  => 14,
            'blood_group' => $donor->blood_group,
        ]);

        $prediction = AiPrediction::where('donor_id', $donor->id)->where('prediction_type', 'eligibility')->first();

        $this->assertNotNull($prediction);
        $this->assertEquals('k-NN', $prediction->model);
    }

    public function test_no_predictions_logged_when_registration_validation_fails(): void
    {
        $this->post(route('donor.register'), [
            'first_name' => '',
        ]);

        $this->assertEquals(0, AiPrediction::count());
    }
}
