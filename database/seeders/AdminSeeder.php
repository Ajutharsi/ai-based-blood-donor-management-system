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
            'email'    => 'admin@lifelink.lk',
            'password' => Hash::make('admin123456'),
            'role'     => 'superadmin',
        ]);
    }
}