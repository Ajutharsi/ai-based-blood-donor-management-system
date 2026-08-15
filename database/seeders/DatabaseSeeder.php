<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Order matters: hospitals/donors must exist before blood requests,
        // which must exist before donor responses; donations only need
        // donors; AI predictions read from both donors and (for
        // shortage/forecast) the already-seeded blood requests.
        $this->call([
            AdminSeeder::class,
            HospitalSeeder::class,
            DonorSeeder::class,
            BloodRequestSeeder::class,
            DonorResponseSeeder::class,
            DonationSeeder::class,
            AiPredictionSeeder::class,
        ]);
    }
}
