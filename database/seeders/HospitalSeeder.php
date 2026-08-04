<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        // Named, realistic Sri Lankan hospitals spread across districts with
        // a deliberate mix of verified/pending so the admin "Hospital
        // Management" filters have something to filter.
        $named = [
            ['name' => 'Colombo National Hospital',        'email' => 'hospital@gmail.com',        'reg' => 'HOS-2024-0001', 'district' => 'Colombo',      'city' => 'Colombo',      'verified' => true],
            ['name' => 'Kandy General Hospital',            'email' => 'kandy.gh@lifelink.test',     'reg' => 'HOS-2024-0002', 'district' => 'Kandy',        'city' => 'Kandy',        'verified' => true],
            ['name' => 'Galle Teaching Hospital',           'email' => 'galle.th@lifelink.test',     'reg' => 'HOS-2024-0003', 'district' => 'Galle',        'city' => 'Galle',        'verified' => true],
            ['name' => 'Jaffna Teaching Hospital',          'email' => 'jaffna.th@lifelink.test',    'reg' => 'HOS-2024-0004', 'district' => 'Jaffna',       'city' => 'Jaffna',       'verified' => true],
            ['name' => 'Kurunegala Base Hospital',          'email' => 'kurunegala.bh@lifelink.test','reg' => 'HOS-2024-0005', 'district' => 'Kurunegala',   'city' => 'Kurunegala',   'verified' => false],
            ['name' => 'Anuradhapura General Hospital',     'email' => 'anuradhapura.gh@lifelink.test','reg' => 'HOS-2024-0006', 'district' => 'Anuradhapura', 'city' => 'Anuradhapura', 'verified' => true],
            ['name' => 'Batticaloa District Hospital',      'email' => 'batticaloa.dh@lifelink.test','reg' => 'HOS-2024-0007', 'district' => 'Batticaloa',   'city' => 'Batticaloa',   'verified' => false],
            ['name' => 'Ratnapura General Hospital',        'email' => 'ratnapura.gh@lifelink.test', 'reg' => 'HOS-2024-0008', 'district' => 'Ratnapura',    'city' => 'Ratnapura',    'verified' => true],
            ['name' => 'Matara General Hospital',           'email' => 'matara.gh@lifelink.test',    'reg' => 'HOS-2024-0009', 'district' => 'Matara',       'city' => 'Matara',       'verified' => false],
            ['name' => 'Gampaha North Colombo Teaching Hospital', 'email' => 'gampaha.th@lifelink.test', 'reg' => 'HOS-2024-0010', 'district' => 'Gampaha', 'city' => 'Ragama', 'verified' => true],
        ];

        foreach ($named as $h) {
            Hospital::updateOrCreate(
                ['email' => $h['email']],
                [
                    'name'            => $h['name'],
                    'password'        => Hash::make('password123'),
                    'registration_id' => $h['reg'],
                    'phone'           => '0' . fake()->numerify('##-#######'),
                    'city'            => $h['city'],
                    'district'        => $h['district'],
                    'address'         => fake()->streetAddress() . ', ' . $h['district'],
                    'is_verified'     => $h['verified'],
                ]
            );
        }

        // Extra randomly generated hospitals for volume/search/filter testing.
        Hospital::factory()->count(6)->create();

        $this->command?->info('  Hospitals seeded: ' . Hospital::count() . ' total (10 named + 6 random)');
        $this->command?->table(
            ['Email', 'Password', 'District', 'Verified'],
            [['hospital@gmail.com', 'password123', 'Colombo', 'Yes (primary demo hospital)']]
        );
    }
}
