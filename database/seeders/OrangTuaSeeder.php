<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrangtuaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orangtua = [];
        
        for ($i = 1; $i <= 100; $i++) {
            $orangtua[] = [
                'id_orangtua' => $i,
                'id_user' => $i + 40, // User IDs 41-140 are parents
                'nama_lengkap' => 'Orangtua ' . $i,
                'alamat' => 'Jalan Keluarga No. ' . $i . ', Kota ' . chr(64 + ($i % 26) + 1),
                'nomor_telepon' => '08' . rand(1000000000, 9999999999),
                'pekerjaan' => ['PNS', 'Wiraswasta', 'Karyawan Swasta', 'Dokter', 'Insinyur', 'Guru', 'Dosen', 'TNI/Polri', 'Petani', 'Nelayan'][$i % 10],
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ];
        }
        
        DB::table('orangtua')->insert($orangtua);
    }
}