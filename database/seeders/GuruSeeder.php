<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guru = [
            [
                'id_user' => 2, // guru1
                'nama_lengkap' => 'Budi Santoso',
                'nip' => '198501152010011001',
                'nomor_telepon' => '081234567890',
                'bidang_studi' => 'Matematika',
                'status' => 'aktif',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'id_user' => 3, // guru2
                'nama_lengkap' => 'Siti Rahayu',
                'nip' => '198703212011012002',
                'nomor_telepon' => '081234567891',
                'bidang_studi' => 'Bahasa Indonesia',
                'status' => 'aktif',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
        ];

        DB::table('guru')->insert($guru);
    }
}