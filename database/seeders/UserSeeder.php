<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Superadmin
        User::factory()->create([
            'name' => 'Superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'superadmin',
        ]);

        // Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // 2 Operasional
        User::factory()->count(2)->create([
            'password' => bcrypt('admin123'),
            'role' => 'operasional',
        ])->each(function ($user, $index) {
            $user->email = 'operasional' . ($index + 1) . '@gmail.com';
            $user->save();
        });

        // 3 Finance
        User::factory()->count(3)->create([
            'password' => bcrypt('admin123'),
            'role' => 'finance',
        ])->each(function ($user, $index) {
            $user->email = 'finance' . ($index + 1) . '@gmail.com';
            $user->save();
        });

        // 3 Sales
        User::factory()->count(3)->create([
            'password' => bcrypt('admin123'),
            'role' => 'sales',
        ])->each(function ($user, $index) {
            $user->email = 'sales' . ($index + 1) . '@gmail.com';
            $user->save();
        });
    }
}