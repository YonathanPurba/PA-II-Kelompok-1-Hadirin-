<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin
            [
                'username' => 'admin',
                'password' => Hash::make('password'),
                'id_role' => 1, // admin
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            // Guru
            [
                'username' => 'guru1',
                'password' => Hash::make('password'),
                'id_role' => 3, // guru
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'username' => 'guru2',
                'password' => Hash::make('password'),
                'id_role' => 3, // guru
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            // Orangtua
            [
                'username' => 'orangtua1',
                'password' => Hash::make('password'),
                'id_role' => 2, // orangtua
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'username' => 'orangtua2',
                'password' => Hash::make('password'),
                'id_role' => 2, // orangtua
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
        ];

        DB::table('users')->insert($users);
    }
}