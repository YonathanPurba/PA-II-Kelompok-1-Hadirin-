<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekapAbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $bulanSekarang = Carbon::now()->format('m');
        $tahunSekarang = Carbon::now()->format('Y');

        for ($siswa = 1; $siswa <= 5; $siswa++) {
            for ($kelas = 1; $kelas <= 2; $kelas++) {
                DB::table('rekap_absensi')->insert([
                    'id_siswa' => $siswa,
                    'id_kelas' => $kelas,
                    'bulan' => $bulanSekarang,
                    'tahun' => $tahunSekarang,
                    'jumlah_hadir' => rand(10, 20),
                    'jumlah_sakit' => rand(0, 3),
                    'jumlah_izin' => rand(0, 3),
                    'jumlah_alpa' => rand(0, 5),
                    'dibuat_pada' => now(),
                    'dibuat_oleh' => 'Seeder',
                    'diperbarui_pada' => now(),
                    'diperbarui_oleh' => 'Seeder',
                ]);
            }
        }
    }
}
