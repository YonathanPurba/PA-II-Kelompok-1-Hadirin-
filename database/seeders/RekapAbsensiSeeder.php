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
        // Get all students
        $siswas = DB::table('siswa')->get();
        
        $rekap = [];
        $id = 1;
        
        // Buat rekap untuk bulan April dan Mei 2025
        foreach (['04', '05'] as $bulan) {
            foreach ($siswas as $siswa) {
                // Hitung jumlah kehadiran berdasarkan data absensi
                $absensi = DB::table('absensi')
                    ->join('jadwal', 'absensi.id_jadwal', '=', 'jadwal.id_jadwal')
                    ->where('absensi.id_siswa', $siswa->id_siswa)
                    ->where('jadwal.id_kelas', $siswa->id_kelas)
                    ->whereRaw("DATE_FORMAT(absensi.tanggal, '%m') = ?", [$bulan])
                    ->whereRaw("DATE_FORMAT(absensi.tanggal, '%Y') = ?", ['2025'])
                    ->select('absensi.status')
                    ->get();
                
                $jumlahHadir = $absensi->where('status', 'hadir')->count();
                $jumlahSakit = $absensi->where('status', 'sakit')->count();
                $jumlahIzin = $absensi->where('status', 'izin')->count();
                $jumlahAlpa = $absensi->where('status', 'alpa')->count();
                
                $rekap[] = [
                    'id_rekap' => $id++,
                    'id_siswa' => $siswa->id_siswa,
                    'id_kelas' => $siswa->id_kelas,
                    'bulan' => $bulan,
                    'tahun' => 2025,
                    'jumlah_hadir' => $jumlahHadir,
                    'jumlah_sakit' => $jumlahSakit,
                    'jumlah_izin' => $jumlahIzin,
                    'jumlah_alpa' => $jumlahAlpa,
                    'dibuat_pada' => Carbon::now(),
                    'dibuat_oleh' => 'system',
                    'diperbarui_pada' => Carbon::now(),
                    'diperbarui_oleh' => 'system'
                ];
                
                // To prevent the data from being too large, we'll limit the number of records
                if ($id > 1000) {
                    break 2; // Break out of both loops
                }
            }
        }
        
        // Insert in chunks to avoid memory issues
        foreach (array_chunk($rekap, 100) as $chunk) {
            DB::table('rekap_absensi')->insert($chunk);
        }
    }
}