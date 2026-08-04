<?php

namespace Database\Factories;

use App\Models\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Donor>
 */
class DonorFactory extends Factory
{
    protected static ?string $password;

    // Sri Lanka's 25 districts, matching the dropdown options already
    // hardcoded in donor_registration_page.blade.php and find_donor.blade.php.
    public const DISTRICTS = [
        'Colombo', 'Gampaha', 'Kandy', 'Galle', 'Matara',
        'Kurunegala', 'Jaffna', 'Batticaloa', 'Anuradhapura', 'Ratnapura',
        'Badulla', 'Trincomalee', 'Kegalle', 'Kalutara', 'Matale',
    ];

    private const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

    private const MEDICAL_CONDITIONS = [
        'Diabetes (controlled)', 'Hypertension (controlled)', 'Asthma', 'Other',
    ];

    public function definition(): array
    {
        $age = fake()->numberBetween(18, 60);
        $isEligible = fake()->boolean(75);

        // AI confidence loosely correlates with the eligibility outcome, the
        // same way the real k-NN model's confidence tends to track its own
        // decision rather than being independent noise.
        $aiConfidence = $isEligible
            ? fake()->randomFloat(2, 60, 99)
            : fake()->randomFloat(2, 5, 55);

        $responseProbability = fake()->randomFloat(2, 10, 95);
        $responseLevel = $responseProbability >= 70 ? 'high' : ($responseProbability >= 40 ? 'medium' : 'low');

        $isAnomaly = fake()->boolean(5); // matches IsolationForest contamination=0.05 in the AI service
        $anomalyScore = $isAnomaly
            ? fake()->randomFloat(2, 70, 99)
            : fake()->randomFloat(2, 0, 30);

        $district = fake()->randomElement(self::DISTRICTS);

        return [
            'first_name'           => fake()->firstName(),
            'last_name'            => fake()->lastName(),
            'email'                => fake()->unique()->safeEmail(),
            'password'             => static::$password ??= Hash::make('password'),
            'phone'                => fake()->numerify('07########'),
            'date_of_birth'        => now()->subYears($age)->subDays(fake()->numberBetween(0, 364))->format('Y-m-d'),
            'age'                  => $age,
            'gender'               => fake()->randomElement(['Male', 'Female', 'Other']),
            'nic'                  => fake()->unique()->numerify('##########V'),
            'blood_group'          => fake()->randomElement(self::BLOOD_GROUPS),
            'weight_kg'            => fake()->randomFloat(2, 48, 95),
            'hemoglobin'           => fake()->randomFloat(1, 11, 17.5),
            'total_donations'      => 0, // reconciled by DonationSeeder from real donation rows
            'last_donation_date'   => null, // reconciled by DonationSeeder
            'city'                 => fake()->city(),
            'district'             => $district,
            'donation_center'      => 'NBTS ' . $district,
            'profile_image'        => null,
            'medical_condition'    => fake()->boolean(25) ? fake()->randomElement(self::MEDICAL_CONDITIONS) : null,
            'medical_notes'        => null,
            'is_eligible'          => $isEligible,
            'ai_confidence'        => $aiConfidence,
            'response_probability' => $responseProbability,
            'response_level'       => $responseLevel,
            'is_anomaly'           => $isAnomaly,
            'anomaly_score'        => $anomalyScore,
            'last_ai_check'        => fake()->boolean(85) ? now()->subDays(fake()->numberBetween(0, 13))->subMinutes(fake()->numberBetween(0, 1439)) : null,
        ];
    }

    public function eligible(): static
    {
        return $this->state(fn () => [
            'is_eligible'   => true,
            'ai_confidence' => fake()->randomFloat(2, 60, 99),
        ]);
    }

    public function notEligible(): static
    {
        return $this->state(fn () => [
            'is_eligible'   => false,
            'ai_confidence' => fake()->randomFloat(2, 5, 55),
        ]);
    }

    public function bloodGroup(string $group): static
    {
        return $this->state(fn () => ['blood_group' => $group]);
    }

    public function district(string $district): static
    {
        return $this->state(fn () => [
            'district'        => $district,
            'donation_center' => 'NBTS ' . $district,
        ]);
    }

    public function anomaly(): static
    {
        return $this->state(fn () => [
            'is_anomaly'    => true,
            'anomaly_score' => fake()->randomFloat(2, 70, 99),
        ]);
    }
}
