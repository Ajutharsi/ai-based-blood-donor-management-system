<?php

namespace Database\Factories;

use App\Models\BloodInventory;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BloodInventory>
 */
class BloodInventoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'hospital_id'       => Hospital::factory(),
            'blood_group'       => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'available_units'   => fake()->numberBetween(20, 60),
            'minimum_threshold' => 10,
            'last_updated'      => now(),
        ];
    }

    public function forHospital(int $hospitalId): static
    {
        return $this->state(fn () => ['hospital_id' => $hospitalId]);
    }

    public function bloodGroup(string $group): static
    {
        return $this->state(fn () => ['blood_group' => $group]);
    }

    public function sufficient(): static
    {
        return $this->state(fn () => ['available_units' => 50, 'minimum_threshold' => 10]);
    }

    public function lowStock(): static
    {
        return $this->state(fn () => ['available_units' => 7, 'minimum_threshold' => 10]);
    }

    public function critical(): static
    {
        return $this->state(fn () => ['available_units' => 2, 'minimum_threshold' => 10]);
    }
}
