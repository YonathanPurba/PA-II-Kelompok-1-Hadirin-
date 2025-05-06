<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruMataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guruMataPelajaran = [
            [
                'id_guru' => 1, // Budi Santoso
                'id_mata_pelajaran' => 1, // Matematika
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'id_guru' => 1, // Budi Santoso
                'id_mata_pelajaran' => 4, // IPA
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'id_guru' => 2, // Siti Rahayu
                'id_mata_pelajaran' => 2, // Bahasa Indonesia
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'id_guru' => 2, // Siti Rahayu
                'id_mata_pelajaran' => 3, // Bahasa Inggris
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
        ];

        DB::table('guru_mata_pelajaran')->insert($guruMataPelajaran);
    }
}