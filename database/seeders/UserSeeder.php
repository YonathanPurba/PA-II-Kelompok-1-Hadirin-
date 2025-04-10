<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Insert Admin
        $adminId = DB::table('users')->insertGetId([
            'username' => 'admin123',
            'password' => Hash::make('password123'),
            'id_role' => 1, // Admin
            'nomor_telepon' => '08123456789',
            'remember_token' => null,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'Seeder',
            'diperbarui_pada' => now(),
            'diperbarui_oleh' => 'Seeder',
        ]);

        // Insert Guru 1
        $guru1Id = DB::table('users')->insertGetId([
            'username' => 'guru_mtk',
            'password' => Hash::make('password123'),
            'id_role' => 2, // Guru
            'nomor_telepon' => '08123456780',
            'remember_token' => null,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'Seeder',
            'diperbarui_pada' => now(),
            'diperbarui_oleh' => 'Seeder',
        ]);

        // Insert Guru 2
        $guru2Id = DB::table('users')->insertGetId([
            'username' => 'guru_bhs',
            'password' => Hash::make('password123'),
            'id_role' => 2, // Guru
            'nomor_telepon' => '08123456781',
            'remember_token' => null,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'Seeder',
            'diperbarui_pada' => now(),
            'diperbarui_oleh' => 'Seeder',
        ]);
        // Insert Orang Tua 1
        $ortu1Id = DB::table('users')->insertGetId([
            'username' => 'ortu1',
            'password' => Hash::make('password123'),
            'id_role' => 3, // Orang Tua
            'nomor_telepon' => '08123456782',
            'remember_token' => null,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'Seeder',
            'diperbarui_pada' => now(),
            'diperbarui_oleh' => 'Seeder',
        ]);

        // Insert Orang Tua 2
        $ortu2Id = DB::table('users')->insertGetId([
            'username' => 'ortu2',
            'password' => Hash::make('password123'),
            'id_role' => 3, // Orang Tua
            'nomor_telepon' => '08123456783',
            'remember_token' => null,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'Seeder',
            'diperbarui_pada' => now(),
            'diperbarui_oleh' => 'Seeder',
        ]);

        // Insert Orang Tua 3
        $ortu3Id = DB::table('users')->insertGetId([
            'username' => 'ortu3',
            'password' => Hash::make('password123'),
            'id_role' => 3, // Orang Tua
            'nomor_telepon' => '08123456784',
            'remember_token' => null,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'Seeder',
            'diperbarui_pada' => now(),
            'diperbarui_oleh' => 'Seeder',
        ]);

        // Insert Orang Tua 4
        $ortu4Id = DB::table('users')->insertGetId([
            'username' => 'ortu4',
            'password' => Hash::make('password123'),
            'id_role' => 3, // Orang Tua
            'nomor_telepon' => '08123456785',
            'remember_token' => null,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'Seeder',
            'diperbarui_pada' => now(),
            'diperbarui_oleh' => 'Seeder',
        ]);

        // Insert Orang Tua 5
        $ortu5Id = DB::table('users')->insertGetId([
            'username' => 'ortu5',
            'password' => Hash::make('password123'),
            'id_role' => 3, // Orang Tua
            'nomor_telepon' => '08123456786',
            'remember_token' => null,
            'dibuat_pada' => now(),
            'dibuat_oleh' => 'Seeder',
            'diperbarui_pada' => now(),
            'diperbarui_oleh' => 'Seeder',
        ]);
    }
}
