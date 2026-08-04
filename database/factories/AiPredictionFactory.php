<?php

namespace Database\Factories;

use App\Models\AiPrediction;
use App\Models\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AiPrediction>
 *
 * Generic definition() produces a standalone eligibility-style row (useful
 * for ad-hoc factory use / tests). The named states below are what the
 * seeder actually uses, so every logged prediction is internally
 * consistent with real request/response shapes the Python AI service
 * returns (see AiEligibilityService and Admin\DashboardController).
 */
class AiPredictionFactory extends Factory
{
    public function definition(): array
    {
        $confidence = fake()->randomFloat(2, 40, 99);

        return [
            'donor_id'        => Donor::factory(),
            'prediction_type' => 'eligibility',
            'model'           => 'k-NN',
            'input'           => [
                'age'             => fake()->numberBetween(18, 60),
                'weight_kg'       => fake()->randomFloat(2, 48, 95),
                'hemoglobin'      => fake()->randomFloat(1, 11, 17.5),
                'total_donations' => fake()->numberBetween(0, 10),
                'blood_group'     => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
                'gender'          => fake()->randomElement(['Male', 'Female', 'Other']),
            ],
            'output' => [
                'eligible'   => $confidence >= 50,
                'confidence' => $confidence,
                'model'      => 'k-NN',
                'status'     => 'success',
            ],
            'confidence' => $confidence,
        ];
    }

    public function eligibilityFor(Donor $donor): static
    {
        return $this->state(fn () => [
            'donor_id'        => $donor->id,
            'prediction_type' => 'eligibility',
            'model'           => 'k-NN',
            'input'           => [
                'age'             => $donor->age,
                'weight_kg'       => (float) $donor->weight_kg,
                'hemoglobin'      => (float) $donor->hemoglobin,
                'total_donations' => $donor->total_donations,
                'blood_group'     => $donor->blood_group,
                'gender'          => $donor->gender,
            ],
            'output' => [
                'eligible'   => (bool) $donor->is_eligible,
                'confidence' => (float) $donor->ai_confidence,
                'model'      => 'k-NN',
                'status'     => 'success',
            ],
            'confidence' => (float) $donor->ai_confidence,
        ]);
    }

    public function responseFor(Donor $donor): static
    {
        return $this->state(fn () => [
            'donor_id'        => $donor->id,
            'prediction_type' => 'response',
            'model'           => 'Random Forest',
            'input'           => [
                'age'         => $donor->age,
                'weight_kg'   => (float) $donor->weight_kg,
                'hemoglobin'  => (float) $donor->hemoglobin,
                'gender'      => $donor->gender,
                'blood_group' => $donor->blood_group,
            ],
            'output' => [
                'response_probability' => (float) $donor->response_probability,
                'level'                => $donor->response_level,
                'model'                => 'Random Forest',
                'status'               => 'success',
            ],
            'confidence' => (float) $donor->response_probability,
        ]);
    }

    public function anomalyFor(Donor $donor): static
    {
        return $this->state(fn () => [
            'donor_id'        => $donor->id,
            'prediction_type' => 'anomaly',
            'model'           => 'Isolation Forest',
            'input'           => [
                'age'             => $donor->age,
                'weight_kg'       => (float) $donor->weight_kg,
                'hemoglobin'      => (float) $donor->hemoglobin,
                'total_donations' => $donor->total_donations,
            ],
            'output' => [
                'is_anomaly'    => (bool) $donor->is_anomaly,
                'anomaly_score' => (float) $donor->anomaly_score,
                'label'         => $donor->is_anomaly ? 'suspicious' : 'normal',
                'model'         => 'Isolation Forest',
                'status'        => 'success',
            ],
            'confidence' => (float) $donor->anomaly_score,
        ]);
    }

    public function shortage(string $bloodGroup, int $eligibleCount, int $totalDonors, int $requestsLastMonth): static
    {
        $level = $eligibleCount === 0 ? 'critical' : ($eligibleCount < 5 ? 'warning' : 'sufficient');
        $confidence = fake()->randomFloat(2, 55, 97);

        return $this->state(fn () => [
            'donor_id'        => null,
            'prediction_type' => 'shortage',
            'model'           => 'k-NN',
            'input'           => [
                'blood_group'         => $bloodGroup,
                'eligible_count'      => $eligibleCount,
                'total_donors'        => $totalDonors,
                'requests_last_month' => $requestsLastMonth,
            ],
            'output' => [
                'level'      => $level,
                'confidence' => $confidence,
                'model'      => 'k-NN',
                'status'     => 'success',
            ],
            'confidence' => $confidence,
        ]);
    }

    public function forecast(string $bloodGroup, array $weeklyCounts, int $eligibleDonors): static
    {
        $avg = count($weeklyCounts) > 0 ? array_sum($weeklyCounts) / count($weeklyCounts) : 0;
        $predicted = max(0, round($avg + fake()->randomFloat(2, -1, 2), 1));
        $confidence = fake()->randomFloat(2, 50, 95);

        return $this->state(fn () => [
            'donor_id'        => null,
            'prediction_type' => 'forecast',
            'model'           => 'Linear Regression',
            'input'           => [
                'blood_group'     => $bloodGroup,
                'weekly_counts'   => $weeklyCounts,
                'eligible_donors' => $eligibleDonors,
            ],
            'output' => [
                'predicted_requests' => $predicted,
                'demand_level'       => $predicted > 2 ? 'high' : ($predicted > 0 ? 'medium' : 'low'),
                'trend'              => fake()->randomElement(['increasing', 'decreasing', 'stable']),
                'model'              => 'Linear Regression',
                'status'             => 'success',
            ],
            'confidence' => $confidence,
        ]);
    }
}
