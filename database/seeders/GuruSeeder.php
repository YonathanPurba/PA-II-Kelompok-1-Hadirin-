<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    public function run()
    {
        $bidangStudi = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA',
            'IPS',
            'Pendidikan Agama',
            'PKN',
            'Pendidikan Jasmani',
            'Seni Budaya',
            'Prakarya'
        ];

        $guruUsers = User::where('id_role', 2)->take(10)->get();

        foreach ($guruUsers as $index => $user) {
            // Cek dulu apakah user ini sudah punya data guru
            if (!Guru::where('id_user', $user->id_user)->exists()) {
                Guru::create([
                    'id_user' => $user->id_user,
                    'nama_lengkap' => 'Guru ' . ($index + 1),
                    'nip' => '1234567890' . ($index + 1),
                    'nomor_telepon' => '08123456' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                    'bidang_studi' => $bidangStudi[$index] ?? 'Umum',
                    'dibuat_pada' => now(),
                    'dibuat_oleh' => 'system',
                    'diperbarui_pada' => now(),
                    'diperbarui_oleh' => 'system',
                ]);
            }
        }
    }
}
