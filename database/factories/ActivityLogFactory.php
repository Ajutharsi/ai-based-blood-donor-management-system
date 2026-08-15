<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'actor_type'   => 'admin',
            'actor_id'     => 1,
            'actor_name'   => 'Admin',
            'category'     => 'admin',
            'action'       => 'donor_eligibility_toggled',
            'description'  => fake()->sentence(8),
            'subject_type' => null,
            'subject_id'   => null,
            'properties'   => [],
            'ip_address'   => '127.0.0.1',
        ];
    }

    public function forActor(string $type, int $id, string $name): static
    {
        return $this->state(fn () => ['actor_type' => $type, 'actor_id' => $id, 'actor_name' => $name]);
    }

    public function category(string $category, string $action): static
    {
        return $this->state(fn () => ['category' => $category, 'action' => $action]);
    }
}
