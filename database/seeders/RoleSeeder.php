<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('role')->insert([
            [
                'id_role' => 1,
                'role' => 'Staff',
                'deskripsi' => 'Memiliki akses penuh ke sistem.',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_role' => 2,
                'role' => 'Guru',
                'deskripsi' => 'Mengelola data siswa dan mengatur jadwal pelajaran.',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_role' => 3,
                'role' => 'Orang Tua',
                'deskripsi' => 'Memiliki Anak.',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ]
        ]);
    }
}
