<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataPelajaran = [
            [
                'nama' => 'Matematika',
                'kode' => 'MTK',
                'deskripsi' => 'Pelajaran tentang angka, ruang, kuantitas dan perubahan',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'nama' => 'Bahasa Indonesia',
                'kode' => 'BIN',
                'deskripsi' => 'Pelajaran tentang bahasa dan sastra Indonesia',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'nama' => 'Bahasa Inggris',
                'kode' => 'BIG',
                'deskripsi' => 'Pelajaran tentang bahasa Inggris',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'nama' => 'Ilmu Pengetahuan Alam',
                'kode' => 'IPA',
                'deskripsi' => 'Pelajaran tentang fenomena alam',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'nama' => 'Ilmu Pengetahuan Sosial',
                'kode' => 'IPS',
                'deskripsi' => 'Pelajaran tentang masyarakat dan interaksi sosial',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
        ];

        DB::table('mata_pelajaran')->insert($mataPelajaran);
    }
}