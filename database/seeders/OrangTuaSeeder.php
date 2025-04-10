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
                'id_user' => 4,
                'nama_lengkap' => 'Budi Santoso',
                'alamat' => 'Jl. Merpati No.12',
                'pekerjaan' => 'Pegawai Negeri',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_user' => 5,
                'nama_lengkap' => 'Siti Aminah',
                'alamat' => 'Jl. Kenari No.45',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_user' => 6,
                'nama_lengkap' => 'Andi Wijaya',
                'alamat' => 'Jl. Melati No.3',
                'pekerjaan' => 'Wiraswasta',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_user' => 7,
                'nama_lengkap' => 'Rina Marlina',
                'alamat' => 'Jl. Flamboyan No.22',
                'pekerjaan' => 'Dosen',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_user' => 8,
                'nama_lengkap' => 'Slamet Hartono',
                'alamat' => 'Jl. Anggrek No.9',
                'pekerjaan' => 'Petani',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'Seeder',
            ],
        ]);
    }
}
