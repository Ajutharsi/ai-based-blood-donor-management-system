<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'LifeLink Admin',
                'password' => Hash::make('password123'),
                'role'     => 'superadmin',
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'reviewer@lifelink.test'],
            [
                'name'     => 'Priya Wickramasinghe',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        Admin::updateOrCreate(
            ['email' => 'support@lifelink.test'],
            [
                'name'     => 'Dinesh Fernando',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        $this->command?->info('  Admins seeded: 3 (login credentials below)');
        $this->command?->table(
            ['Email', 'Password', 'Role'],
            [
                ['admin@gmail.com', 'password123', 'superadmin'],
                ['reviewer@lifelink.test', 'password123', 'admin'],
                ['support@lifelink.test', 'password123', 'admin'],
            ]
        );
    }
}
