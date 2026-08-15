<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_type'  => 'donor',
            'user_id'    => 1,
            'title'      => fake()->sentence(3),
            'message'    => fake()->sentence(12),
            'type'       => 'blood_request',
            'related_id' => null,
            'read_at'    => null,
        ];
    }

    public function forDonor(int $donorId): static
    {
        return $this->state(fn () => ['user_type' => 'donor', 'user_id' => $donorId]);
    }

    public function forHospital(int $hospitalId): static
    {
        return $this->state(fn () => ['user_type' => 'hospital', 'user_id' => $hospitalId]);
    }

    public function forAdmin(int $adminId): static
    {
        return $this->state(fn () => ['user_type' => 'admin', 'user_id' => $adminId]);
    }

    public function read(): static
    {
        return $this->state(fn () => ['read_at' => now()]);
    }

    public function unread(): static
    {
        return $this->state(fn () => ['read_at' => null]);
    }
}
