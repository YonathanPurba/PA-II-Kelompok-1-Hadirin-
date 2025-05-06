<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RekapAbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bulan dan tahun saat ini
        $bulanIni = Carbon::now()->format('m');
        $tahunIni = Carbon::now()->format('Y');
        
        // Siswa dan kelas
        $siswaKelas = [
            ['id_siswa' => 1, 'id_kelas' => 1], // Andi di kelas 7A
            ['id_siswa' => 2, 'id_kelas' => 2], // Budi di kelas 7B
            ['id_siswa' => 3, 'id_kelas' => 1], // Citra di kelas 7A
        ];
        
        $rekapAbsensi = [];
        
        foreach ($siswaKelas as $sk) {
            // Hitung jumlah kehadiran dari tabel absensi (ini hanya simulasi, seharusnya query ke DB)
            // Untuk contoh, kita buat data random
            $jumlahHadir = rand(15, 20);
            $jumlahSakit = rand(0, 3);
            $jumlahIzin = rand(0, 2);
            $jumlahAlpa = rand(0, 1);
            
            $rekapAbsensi[] = [
                'id_siswa' => $sk['id_siswa'],
                'id_kelas' => $sk['id_kelas'],
                'bulan' => $bulanIni,
                'tahun' => $tahunIni,
                'jumlah_hadir' => $jumlahHadir,
                'jumlah_sakit' => $jumlahSakit,
                'jumlah_izin' => $jumlahIzin,
                'jumlah_alpa' => $jumlahAlpa,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'seeder'
            ];
        }
        
        // Insert batch rekap absensi
        DB::table('rekap_absensi')->insert($rekapAbsensi);
    }
}