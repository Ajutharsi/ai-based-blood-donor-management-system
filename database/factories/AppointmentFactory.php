<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'blood_request_id' => BloodRequest::factory(),
            'donor_id'         => Donor::factory(),
            'hospital_id'      => function (array $attributes) {
                return BloodRequest::find($attributes['blood_request_id'])?->hospital_id
                    ?? \App\Models\Hospital::factory()->create()->id;
            },
            'appointment_date' => fake()->dateTimeBetween('now', '+3 weeks')->format('Y-m-d'),
            'appointment_time' => fake()->randomElement(['09:00', '10:30', '13:00', '14:30', '16:00']),
            'status'           => 'pending',
            'notes'            => null,
        ];
    }

    public function status(string $status): static
    {
        return $this->state(fn () => ['status' => $status]);
    }
}
