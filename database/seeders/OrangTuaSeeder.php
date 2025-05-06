<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrangtuaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orangtua = [
            [
                'id_user' => 4, // orangtua1
                'nama_lengkap' => 'Ahmad Wijaya',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'nomor_telepon' => '081234567892',
                'pekerjaan' => 'Wiraswasta',
                'status' => 'aktif',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
            [
                'id_user' => 5, // orangtua2
                'nama_lengkap' => 'Dewi Susanti',
                'alamat' => 'Jl. Pahlawan No. 45, Jakarta',
                'nomor_telepon' => '081234567893',
                'pekerjaan' => 'Pegawai Swasta',
                'status' => 'aktif',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ],
        ];

        DB::table('orangtua')->insert($orangtua);
    }
}