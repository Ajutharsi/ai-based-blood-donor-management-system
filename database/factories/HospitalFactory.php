<?php

namespace Database\Factories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Hospital>
 */
class HospitalFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'            => fake()->company() . ' Hospital',
            'email'           => fake()->unique()->safeEmail(),
            'password'        => static::$password ??= Hash::make('password'),
            'registration_id' => fake()->unique()->numerify('HOS-####'),
            'phone'           => fake()->numerify('011#######'),
            'city'            => fake()->city(),
            'district'        => 'Colombo',
            'address'         => fake()->streetAddress(),
            'is_verified'     => true,
        ];
    }
}
