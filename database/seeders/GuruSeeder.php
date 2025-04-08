<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        // Insert ke tabel guru
        DB::table('guru')->insert([
            [
                'id_user' => 2,
                'nama_lengkap' => 'Budi Santoso', // ✅ Tambahkan nama_lengkap
                'nip' => '1987654321',
                'bidang_studi' => 'Matematika',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_user' => 3,
                'nama_lengkap' => 'Siti Aminah', // ✅ Tambahkan nama_lengkap
                'nip' => '1987654322',
                'bidang_studi' => 'Bahasa Indonesia',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
        ]);
    }
}
