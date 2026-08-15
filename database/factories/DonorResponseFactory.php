<?php

namespace Database\Factories;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\DonorResponse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DonorResponse>
 */
class DonorResponseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'donor_id'         => Donor::factory(),
            'blood_request_id' => BloodRequest::factory(),
            'status'           => fake()->randomElement(['available', 'available', 'not_available']),
            'responded_at'     => fake()->dateTimeBetween('-3 weeks', 'now'),
        ];
    }

    public function available(): static
    {
        return $this->state(fn () => ['status' => 'available']);
    }

    public function notAvailable(): static
    {
        return $this->state(fn () => ['status' => 'not_available']);
    }
}
