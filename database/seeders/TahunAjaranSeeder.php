<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAjaran = [];
        $id = 1;
        
        // Generate academic years from 2020-2021 to 2026-2027
        for ($tahun = 2020; $tahun <= 2026; $tahun++) {
            $tahunAjaran[] = [
                'id_tahun_ajaran' => $id++,
                'nama_tahun_ajaran' => $tahun . '/' . ($tahun + 1) . ' Semester Ganjil',
                'tanggal_mulai' => Carbon::createFromDate($tahun, 7, 15)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::createFromDate($tahun, 12, 20)->format('Y-m-d'),
                'aktif' => ($tahun == 2025) ? 1 : 0,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ];
            
            $tahunAjaran[] = [
                'id_tahun_ajaran' => $id++,
                'nama_tahun_ajaran' => $tahun . '/' . ($tahun + 1) . ' Semester Genap',
                'tanggal_mulai' => Carbon::createFromDate($tahun + 1, 1, 5)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::createFromDate($tahun + 1, 6, 15)->format('Y-m-d'),
                'aktif' => 0,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ];
        }
        
        DB::table('tahun_ajaran')->insert($tahunAjaran);
    }
}