<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruMataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('guru_mata_pelajaran')->insert([
            [
                'id_guru' => 1,
                'id_mata_pelajaran' => 1,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_guru' => 1,
                'id_mata_pelajaran' => 3, // Fisika
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_guru' => 2,
                'id_mata_pelajaran' => 2, // Bahasa Indonesia
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
        ]);
    }
}
