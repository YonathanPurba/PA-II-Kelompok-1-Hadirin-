<?php

namespace Database\Seeders;

use App\Models\Guru;
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

        for ($i = 1; $i <= 10; $i++) {
            Guru::create([
                'id_user' => $i + 1, // User ID mulai dari 2 (setelah admin)
                'nama_lengkap' => 'Guru ' . $i,
                'nip' => '1234567890' . $i,
                'nomor_telepon' => '08123456' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'bidang_studi' => $bidangStudi[$i - 1],
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ]);
        }
    }
}