<?php

namespace Database\Seeders;

use App\Models\Donor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DonorSeeder extends Seeder
{
    private const BLOOD_GROUPS = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

    public function run(): void
    {
        // Primary demo donor login -- kept identical to the original seed so
        // existing docs/manual test notes referencing it still work.
        Donor::updateOrCreate(
            ['email' => 'donor@gmail.com'],
            [
                'first_name'      => 'Test',
                'last_name'       => 'Donor',
                'password'        => Hash::make('password123'),
                'phone'           => '0771234567',
                'date_of_birth'   => '2000-01-01',
                'age'             => 26,
                'gender'          => 'Other',
                'nic'             => '200000000001V',
                'blood_group'     => 'O+',
                'weight_kg'       => 65,
                'hemoglobin'      => 14.0,
                'total_donations' => 0,
                'city'            => 'Colombo',
                'district'        => 'Colombo',
                'is_eligible'     => true,
                'ai_confidence'   => 85.0,
                'response_probability' => 72.0,
                'response_level'  => 'high',
                'is_anomaly'      => false,
                'anomaly_score'   => 4.5,
                'last_ai_check'   => now()->subHours(6),
            ]
        );

        // A handful of hand-picked "storyline" donors covering the edge
        // cases a demo walkthrough needs: a rare-group high-confidence
        // donor, a flagged anomaly, an ineligible donor, and a low
        // response-probability donor.
        $storyline = [
            ['first_name' => 'Nadeesha', 'last_name' => 'Rathnayake', 'email' => 'nadeesha.r@lifelink.test', 'blood_group' => 'AB-', 'district' => 'Kandy',     'is_eligible' => true,  'ai_confidence' => 96.4, 'response_probability' => 88.0, 'response_level' => 'high',   'is_anomaly' => false, 'anomaly_score' => 3.1],
            ['first_name' => 'Kasun',    'last_name' => 'Jayasuriya',  'email' => 'kasun.j@lifelink.test',   'blood_group' => 'O-',  'district' => 'Galle',      'is_eligible' => true,  'ai_confidence' => 91.2, 'response_probability' => 79.5, 'response_level' => 'high',   'is_anomaly' => false, 'anomaly_score' => 6.0],
            ['first_name' => 'Ishara',   'last_name' => 'Bandara',     'email' => 'ishara.b@lifelink.test',  'blood_group' => 'B+',  'district' => 'Jaffna',     'is_eligible' => false, 'ai_confidence' => 32.0, 'response_probability' => 21.0, 'response_level' => 'low',    'is_anomaly' => false, 'anomaly_score' => 12.0],
            ['first_name' => 'Tharindu', 'last_name' => 'Perera',      'email' => 'tharindu.p@lifelink.test','blood_group' => 'A+',  'district' => 'Kurunegala', 'is_eligible' => true,  'ai_confidence' => 78.0, 'response_probability' => 34.0, 'response_level' => 'low',    'is_anomaly' => true,  'anomaly_score' => 82.0],
            ['first_name' => 'Chamodi',  'last_name' => 'Wijesinghe',  'email' => 'chamodi.w@lifelink.test', 'blood_group' => 'AB+', 'district' => 'Ratnapura',  'is_eligible' => true,  'ai_confidence' => 87.5, 'response_probability' => 65.0, 'response_level' => 'medium', 'is_anomaly' => false, 'anomaly_score' => 9.0],
        ];

        foreach ($storyline as $i => $d) {
            Donor::updateOrCreate(
                ['email' => $d['email']],
                [
                    'first_name'           => $d['first_name'],
                    'last_name'            => $d['last_name'],
                    'password'             => Hash::make('password123'),
                    'phone'                => fake()->numerify('07########'),
                    'date_of_birth'        => now()->subYears(fake()->numberBetween(20, 55))->format('Y-m-d'),
                    'age'                  => fake()->numberBetween(20, 55),
                    'gender'               => fake()->randomElement(['Male', 'Female', 'Other']),
                    'nic'                  => fake()->unique()->numerify('##########V'),
                    'blood_group'          => $d['blood_group'],
                    'weight_kg'            => fake()->randomFloat(2, 52, 88),
                    'hemoglobin'           => fake()->randomFloat(1, 12, 16.5),
                    'total_donations'      => 0,
                    'city'                 => $d['district'],
                    'district'             => $d['district'],
                    'donation_center'      => 'NBTS ' . $d['district'],
                    'is_eligible'          => $d['is_eligible'],
                    'ai_confidence'        => $d['ai_confidence'],
                    'response_probability' => $d['response_probability'],
                    'response_level'       => $d['response_level'],
                    'is_anomaly'           => $d['is_anomaly'],
                    'anomaly_score'        => $d['anomaly_score'],
                    'last_ai_check'        => now()->subHours($i + 1),
                ]
            );
        }

        // Bulk-generate donors so every blood group has healthy volume for
        // compatibility/matching tests, the AI dashboard's per-group
        // shortage/forecast cards, and the public find-donors filters.
        foreach (self::BLOOD_GROUPS as $group) {
            Donor::factory()
                ->count(20)
                ->bloodGroup($group)
                ->create();
        }

        $this->command?->info('  Donors seeded: ' . Donor::count() . ' total (1 primary demo + 5 storyline + 160 bulk, covering all 8 blood groups)');
        $this->command?->table(
            ['Email', 'Password', 'Blood Group', 'District'],
            [['donor@gmail.com', 'password123', 'O+', 'Colombo (primary demo donor)']]
        );
    }
}
