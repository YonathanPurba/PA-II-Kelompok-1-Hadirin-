<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kelas')->insert([
            [
                'nama_kelas' => 'Kelas 1A',
                'tingkat' => '1',
                'id_guru_wali' => 1, // pastikan guru dengan ID 1 ada
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'admin',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'admin'
            ],
            [
                'nama_kelas' => 'Kelas 2A',
                'tingkat' => '2',
                'id_guru_wali' => 2,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'admin',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'admin'
            ],
            // [
            //     'nama_kelas' => 'Kelas 3B',
            //     'tingkat' => '3',
            //     'id_guru_wali' => 3,
            //     'dibuat_pada' => Carbon::now(),
            //     'dibuat_oleh' => 'admin',
            //     'diperbarui_pada' => Carbon::now(),
            //     'diperbarui_oleh' => 'admin'
            // ],
        ]);
    }
}
