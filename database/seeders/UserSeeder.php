<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'id_role' => 1,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'system'
        ]);

        // Guru
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'username' => 'guru' . $i,
                'password' => Hash::make('password'),
                'id_role' => 2,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ]);
        }

        // Orangtua
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'username' => 'orangtua' . $i,
                'password' => Hash::make('password'),
                'id_role' => 3,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ]);
        }
    }
}