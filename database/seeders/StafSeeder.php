<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StafSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatan = ['Kepala Sekolah', 'Wakil Kepala Sekolah', 'Tata Usaha', 'Bendahara', 'Pustakawan', 'Petugas Kebersihan', 'Petugas Keamanan', 'Teknisi IT', 'Administrasi', 'Petugas Kesehatan'];
        $staf = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $staf[] = [
                'id_staf' => $i,
                'id_user' => $i, // User IDs 1-10 are staff
                'nama_lengkap' => 'Staf ' . $i,
                'nip' => '1980' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nomor_telepon' => '08' . rand(1000000000, 9999999999),
                'jabatan' => $jabatan[$i - 1],
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ];
        }
        
        DB::table('staf')->insert($staf);
    }
}