<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guru = [];
        
        for ($i = 1; $i <= 30; $i++) {
            $guru[] = [
                'id_guru' => $i,
                'id_user' => $i + 10, // User IDs 11-40 are teachers
                'nama_lengkap' => 'Guru ' . $i,
                'nip' => '1985' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nomor_telepon' => '08' . rand(1000000000, 9999999999),
                'bidang_studi' => ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS', 'Pendidikan Agama', 'PPKN', 'Seni Budaya', 'Pendidikan Jasmani', 'Prakarya'][$i % 10],
                'status' => 'aktif',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ];
        }
        
        DB::table('guru')->insert($guru);
    }
}