<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            [
                'nama_kelas' => '7A',
                'tingkat' => '7',
                'id_guru' => 1, // Budi Santoso (wali kelas)
                'id_tahun_ajaran' => 1, // 2024/2025
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'nama_kelas' => '7B',
                'tingkat' => '7',
                'id_guru' => 2, // Siti Rahayu (wali kelas)
                'id_tahun_ajaran' => 1, // 2024/2025
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
        ];

        DB::table('kelas')->insert($kelas);
    }
}