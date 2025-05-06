<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = [
            [
                'nama' => 'Andi Wijaya',
                'nis' => '2024001',
                'id_orangtua' => 1, // Ahmad Wijaya
                'id_kelas' => 1, // 7A
                'id_tahun_ajaran' => 1, // 2024/2025
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2011-05-15',
                'jenis_kelamin' => 'laki-laki',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'status' => 'aktif',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'nama' => 'Budi Wijaya',
                'nis' => '2024002',
                'id_orangtua' => 1, // Ahmad Wijaya
                'id_kelas' => 2, // 7B
                'id_tahun_ajaran' => 1, // 2024/2025
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2012-08-20',
                'jenis_kelamin' => 'laki-laki',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'status' => 'aktif',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'nama' => 'Citra Susanti',
                'nis' => '2024003',
                'id_orangtua' => 2, // Dewi Susanti
                'id_kelas' => 1, // 7A
                'id_tahun_ajaran' => 1, // 2024/2025
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2011-03-10',
                'jenis_kelamin' => 'perempuan',
                'alamat' => 'Jl. Pahlawan No. 45, Jakarta',
                'status' => 'aktif',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
        ];

        DB::table('siswa')->insert($siswa);
    }
}