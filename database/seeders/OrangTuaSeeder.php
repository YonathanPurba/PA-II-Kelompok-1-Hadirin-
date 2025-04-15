<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class OrangtuaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orangtua')->insert([
            [
                'id_user' => 2,
                'nama_lengkap' => 'Budi Santoso',
                'alamat' => 'Jl. Merpati No.12',
                'pekerjaan' => 'Pegawai Negeri',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'Seeder',
            ],
        ]);
    }
}
