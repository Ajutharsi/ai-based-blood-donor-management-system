<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name'     => 'LifeLink Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'superadmin',
        ]);
    }
}