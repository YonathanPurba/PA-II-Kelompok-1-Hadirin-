<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TahunAjaranSeeder extends Seeder
{
    public function run()
    {
        DB::table('tahun_ajaran')->insert([
            [
                'nama_tahun_ajaran' => '2023/2024',
                'tanggal_mulai' => Carbon::create('2023', '08', '01'), // 1 Agustus 2023
                'tanggal_selesai' => Carbon::create('2024', '07', '31'), // 31 Juli 2024
                'aktif' => true,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'admin',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'admin',
            ],
            [
                'nama_tahun_ajaran' => '2024/2025',
                'tanggal_mulai' => Carbon::create('2024', '08', '01'), // 1 Agustus 2024
                'tanggal_selesai' => Carbon::create('2025', '07', '31'), // 31 Juli 2025
                'aktif' => false,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'admin',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'admin',
            ],
        ]);
    }
}
