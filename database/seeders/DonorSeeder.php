<?php

namespace Database\Seeders;

use App\Models\Donor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DonorSeeder extends Seeder
{
    public function run(): void
    {
        Donor::create([
            'first_name'      => 'Test',
            'last_name'       => 'Donor',
            'email'           => 'donor@gmail.com',
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
        ]);
    }
}
