<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwal = [];
        $id = 1;
        
        // Define time sessions as requested
        $sesi = [
            1 => ['mulai' => '07:45:00', 'selesai' => '08:30:00'],
            2 => ['mulai' => '08:30:00', 'selesai' => '09:15:00'],
            3 => ['mulai' => '09:15:00', 'selesai' => '10:00:00'],
            4 => ['mulai' => '10:15:00', 'selesai' => '11:00:00'], // After 15 min break
            5 => ['mulai' => '11:00:00', 'selesai' => '11:45:00'],
            6 => ['mulai' => '11:45:00', 'selesai' => '12:30:00'],
        ];
        
        $hari = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        
        // Get all classes
        $allKelas = DB::table('kelas')->get();
        
        // Get active tahun ajaran
        $tahunAjaranAktif = DB::table('tahun_ajaran')->where('aktif', 1)->first();
        $idTahunAjaran = $tahunAjaranAktif ? $tahunAjaranAktif->id_tahun_ajaran : 1;
        
        // Get all teachers and subjects
        $allGuru = DB::table('guru')->get();
        $allMapel = DB::table('mata_pelajaran')->get();
        
        // For each class, create a schedule for each day and session
        foreach ($allKelas as $kelas) {
            foreach ($hari as $h) {
                foreach ($sesi as $s => $waktu) {
                    // Skip Friday afternoon (only 4 sessions on Friday)
                    if ($h == 'jumat' && $s > 4) {
                        continue;
                    }
                    
                    // Randomly assign a teacher and subject
                    $randomGuru = $allGuru[array_rand($allGuru->toArray())];
                    $randomMapel = $allMapel[array_rand($allMapel->toArray())];
                    
                    $jadwal[] = [
                        'id_jadwal' => $id++,
                        'id_kelas' => $kelas->id_kelas,
                        'id_mata_pelajaran' => $randomMapel->id_mata_pelajaran,
                        'id_guru' => $randomGuru->id_guru,
                        'id_tahun_ajaran' => $idTahunAjaran,
                        'hari' => $h,
                        'waktu_mulai' => $waktu['mulai'],
                        'waktu_selesai' => $waktu['selesai'],
                        'status' => 'aktif',
                        'dibuat_pada' => Carbon::now(),
                        'dibuat_oleh' => 'system',
                        'diperbarui_pada' => Carbon::now(),
                        'diperbarui_oleh' => 'system'
                    ];
                }
            }
        }
        
        DB::table('jadwal')->insert($jadwal);
    }
}