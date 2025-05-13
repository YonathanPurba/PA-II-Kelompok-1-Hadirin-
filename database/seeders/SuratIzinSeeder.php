<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuratIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suratIzin = [];
        $id = 1;
        
        // Get all students
        $allSiswa = DB::table('siswa')->get();
        
        // Generate permission letters for April and May 2025
        $months = [4, 5]; // April and May
        $year = 2025;
        
        $alasanIzin = [
            'Sakit',
            'Acara Keluarga',
            'Pemeriksaan Kesehatan',
            'Kegiatan Lomba',
            'Kegiatan Keagamaan',
            'Urusan Pribadi',
            'Musibah Keluarga',
            'Kegiatan Ekstrakurikuler',
            'Perjalanan Keluarga',
            'Lainnya'
        ];
        
        // For each student, create 0-2 permission letters
        foreach ($allSiswa as $siswa) {
            $numLetters = rand(0, 2);
            
            for ($i = 0; $i < $numLetters; $i++) {
                $month = $months[array_rand($months)];
                $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
                $day = rand(1, $daysInMonth);
                $date = Carbon::createFromDate($year, $month, $day);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }
                
                $durationDays = rand(1, 3);
                $jenis = rand(0, 1) ? 'sakit' : 'izin';
                $alasan = $alasanIzin[array_rand($alasanIzin)];
                
                $suratIzin[] = [
                    'id_surat_izin' => $id++,
                    'id_siswa' => $siswa->id_siswa,
                    'id_orangtua' => $siswa->id_orangtua,
                    'jenis' => $jenis,
                    'tanggal_mulai' => $date->format('Y-m-d'),
                    'tanggal_selesai' => $date->copy()->addDays($durationDays - 1)->format('Y-m-d'),
                    'alasan' => $alasan,
                    'file_lampiran' => null,
                    'status' => ['menunggu', 'disetujui', 'ditolak'][rand(0, 2)],
                    'dibuat_pada' => Carbon::now(),
                    'dibuat_oleh' => 'system',
                    'diperbarui_pada' => Carbon::now(),
                    'diperbarui_oleh' => 'system'
                ];
            }
        }
        
        DB::table('surat_izin')->insert($suratIzin);
    }
}