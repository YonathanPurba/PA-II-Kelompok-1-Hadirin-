<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwal = [
            // Jadwal Kelas 7A
            [
                'id_kelas' => 1, // 7A
                'id_mata_pelajaran' => 1, // Matematika
                'id_guru' => 1, // Budi Santoso
                'hari' => 'senin',
                'waktu_mulai' => '07:30:00',
                'waktu_selesai' => '09:00:00',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'id_kelas' => 1, // 7A
                'id_mata_pelajaran' => 2, // Bahasa Indonesia
                'id_guru' => 2, // Siti Rahayu
                'hari' => 'senin',
                'waktu_mulai' => '09:15:00',
                'waktu_selesai' => '10:45:00',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'id_kelas' => 1, // 7A
                'id_mata_pelajaran' => 3, // Bahasa Inggris
                'id_guru' => 2, // Siti Rahayu
                'hari' => 'selasa',
                'waktu_mulai' => '07:30:00',
                'waktu_selesai' => '09:00:00',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            
            // Jadwal Kelas 7B
            [
                'id_kelas' => 2, // 7B
                'id_mata_pelajaran' => 1, // Matematika
                'id_guru' => 1, // Budi Santoso
                'hari' => 'selasa',
                'waktu_mulai' => '09:15:00',
                'waktu_selesai' => '10:45:00',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'id_kelas' => 2, // 7B
                'id_mata_pelajaran' => 2, // Bahasa Indonesia
                'id_guru' => 2, // Siti Rahayu
                'hari' => 'rabu',
                'waktu_mulai' => '07:30:00',
                'waktu_selesai' => '09:00:00',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
        ];

        DB::table('jadwal')->insert($jadwal);
    }
}