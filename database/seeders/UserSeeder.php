<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'username' => 'admin123',
                'password' => Hash::make('password123'),
                'role' => 'staf',
                'remember_token' => null,
                'dibuat_pada' => $now,
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => $now,
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'username' => 'guru_mtk',
                'password' => Hash::make('password123'),
                'role' => 'guru',
                'remember_token' => null,
                'dibuat_pada' => $now,
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => $now,
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'username' => 'ortu1',
                'password' => Hash::make('password123'),
                'role' => 'ortu',
                'remember_token' => null,
                'dibuat_pada' => $now,
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => $now,
                'diperbarui_oleh' => 'Seeder',
            ]
        ]);
    }
}
