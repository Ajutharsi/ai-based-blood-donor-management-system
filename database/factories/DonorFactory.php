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

    public function definition(): array
    {
        return [
            'first_name'      => fake()->firstName(),
            'last_name'       => fake()->lastName(),
            'email'           => fake()->unique()->safeEmail(),
            'password'        => static::$password ??= Hash::make('password'),
            'phone'           => fake()->numerify('07########'),
            'date_of_birth'   => fake()->dateTimeBetween('-45 years', '-18 years')->format('Y-m-d'),
            'age'             => fake()->numberBetween(18, 45),
            'gender'          => fake()->randomElement(['Male', 'Female', 'Other']),
            'nic'             => fake()->unique()->numerify('##########V'),
            'blood_group'     => fake()->randomElement(['A+','A-','B+','B-','O+','O-','AB+','AB-']),
            'weight_kg'       => fake()->numberBetween(50, 90),
            'hemoglobin'      => fake()->randomFloat(1, 12, 17),
            'total_donations' => 0,
            'city'            => fake()->city(),
            'district'        => 'Colombo',
            'is_eligible'     => true,
            'ai_confidence'   => 85.0,
        ];
    }
}
