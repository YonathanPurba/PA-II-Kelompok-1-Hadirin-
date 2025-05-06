<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaran = [
            [
                'nama_tahun_ajaran' => '2024/2025',
                'tanggal_mulai' => '2024-07-15',
                'tanggal_selesai' => '2025-06-30',
                'aktif' => true,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'nama_tahun_ajaran' => '2025/2026',
                'tanggal_mulai' => '2025-07-15',
                'tanggal_selesai' => '2026-06-30',
                'aktif' => false,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
        ];

        DB::table('tahun_ajaran')->insert($tahunAjaran);
    }
}