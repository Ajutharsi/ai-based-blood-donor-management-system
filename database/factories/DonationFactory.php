<?php

namespace Database\Factories;

use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'donor_id'        => Donor::factory(),
            'donation_date'   => fake()->dateTimeBetween('-2 years', '-1 week')->format('Y-m-d'),
            'blood_group'     => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'donation_center' => 'NBTS ' . fake()->randomElement(['Colombo', 'Kandy', 'Galle', 'Jaffna', 'Kurunegala']),
            'units'           => fake()->numberBetween(1, 2),
            'notes'           => fake()->boolean(15) ? fake()->sentence() : null,
        ];
    }
}
