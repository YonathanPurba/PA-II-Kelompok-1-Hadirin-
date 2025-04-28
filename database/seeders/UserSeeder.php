<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guru;
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
            $user = User::create([
                'username' => 'guru' . $i,
                'password' => Hash::make('password'),
                'id_role' => 2,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ]);

            Guru::create([
                'id_user' => $user->id_user,
                'nama_lengkap' => 'Guru ' . $i,
                'nip' => '1987' . str_pad($user->id_user, 6, '0', STR_PAD_LEFT),
                'nomor_telepon' => '08' . rand(1000000000, 9999999999),
                'bidang_studi' => 'Matematika',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'system'
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
