<?php

namespace Database\Seeders;

use App\Models\Hospital;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HospitalSeeder extends Seeder
{
    public function run(): void
    {
        Hospital::create([
            'name'            => 'Colombo National Hospital',
            'email'           => 'colombo@hospital.lk',
            'password'        => Hash::make('hospital123'),
            'registration_id' => 'HOS-2024-0001',
            'phone'           => '+94 11 269 1111',
            'city'            => 'Colombo',
            'district'        => 'Colombo',
            'address'         => 'Regent Street, Colombo 08',
            'is_verified'     => true,
        ]);
    }
}