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

    private const DISTRICTS = [
        'Colombo', 'Gampaha', 'Kandy', 'Galle', 'Matara',
        'Kurunegala', 'Jaffna', 'Batticaloa', 'Anuradhapura', 'Ratnapura',
        'Badulla', 'Trincomalee', 'Kegalle', 'Kalutara', 'Matale',
    ];

    public function definition(): array
    {
        $district = fake()->randomElement(self::DISTRICTS);

        return [
            'name'            => fake()->city() . ' ' . fake()->randomElement(['General Hospital', 'Base Hospital', 'Teaching Hospital', 'District Hospital']),
            'email'           => fake()->unique()->safeEmail(),
            'password'        => static::$password ??= Hash::make('password'),
            'registration_id' => 'HOS-' . fake()->unique()->numerify('####-####'),
            'phone'           => fake()->numerify('0##-#######'),
            'city'            => fake()->city(),
            'district'        => $district,
            'address'         => fake()->streetAddress() . ', ' . $district,
            'is_verified'     => fake()->boolean(70),
        ];
    }

    public function verified(): static
    {
        return $this->state(fn () => ['is_verified' => true]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['is_verified' => false]);
    }

    public function district(string $district): static
    {
        return $this->state(fn () => [
            'district' => $district,
            'address'  => fake()->streetAddress() . ', ' . $district,
        ]);
    }
}
