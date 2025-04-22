<?php

namespace Database\Seeders;

use App\Models\Orangtua;
use Illuminate\Database\Seeder;

class OrangtuaSeeder extends Seeder
{
    public function run()
    {
        $pekerjaan = [
            'Pegawai Negeri Sipil',
            'Karyawan Swasta',
            'Wiraswasta',
            'Dokter',
            'Guru',
            'Petani',
            'Pedagang',
            'TNI/Polri',
            'Buruh',
            'Ibu Rumah Tangga'
        ];

        for ($i = 1; $i <= 20; $i++) {
            Orangtua::create([
                'id_user' => $i + 11, // User ID mulai dari 12 (setelah guru)
                'nama_lengkap' => 'Orangtua ' . $i,
                'alamat' => 'Jl. Contoh No. ' . $i . ', Kota Contoh',
                'nomor_telepon' => '08567890' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'pekerjaan' => $pekerjaan[array_rand($pekerjaan)],
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ]);
        }
    }
}