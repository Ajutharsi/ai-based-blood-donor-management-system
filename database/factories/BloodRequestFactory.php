<?php

namespace Database\Factories;

use App\Models\BloodRequest;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BloodRequest>
 */
class BloodRequestFactory extends Factory
{
    private const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
    private const URGENCIES = ['standard', 'urgent', 'critical'];
    private const WARDS = ['ICU', 'Emergency', 'Surgical', 'Maternity', 'General Medicine', 'Oncology', 'Pediatric'];

    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'pending', 'fulfilled', 'fulfilled', 'cancelled']);

        return [
            'hospital_id'  => Hospital::factory(),
            'blood_group'  => fake()->randomElement(self::BLOOD_GROUPS),
            'units_needed' => fake()->numberBetween(1, 8),
            'urgency'      => fake()->randomElement(self::URGENCIES),
            'ward'         => fake()->randomElement(self::WARDS),
            'required_by'  => fake()->boolean(70) ? now()->addDays(fake()->numberBetween(1, 21))->format('Y-m-d') : null,
            'notes'        => fake()->boolean(30) ? fake()->sentence() : null,
            'status'       => $status,
        ];
    }

    public function urgency(string $urgency): static
    {
        return $this->state(fn () => ['urgency' => $urgency]);
    }

    public function status(string $status): static
    {
        return $this->state(fn () => ['status' => $status]);
    }

    public function bloodGroup(string $group): static
    {
        return $this->state(fn () => ['blood_group' => $group]);
    }
}
