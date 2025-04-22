<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run()
    {
        $tahunAjaran = [
            [
                'nama_tahun_ajaran' => '2023/2024',
                'tanggal_mulai' => '2023-07-17',
                'tanggal_selesai' => '2024-06-30',
                'aktif' => true,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ],
            [
                'nama_tahun_ajaran' => '2024/2025',
                'tanggal_mulai' => '2024-07-15',
                'tanggal_selesai' => '2025-06-30',
                'aktif' => false,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ]
        ];

        foreach ($tahunAjaran as $ta) {
            TahunAjaran::create($ta);
        }
    }
}