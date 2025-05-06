<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tanggal untuk absensi (misalnya 2 minggu terakhir)
        $tanggalMulai = Carbon::now()->subDays(14);
        
        // Siswa yang akan diberi absensi
        $siswaIds = [1, 2, 3]; // Andi, Budi, Citra
        
        // Jadwal yang akan diberi absensi
        $jadwalIds = [1, 2, 3, 4, 5]; // Jadwal dari seeder JadwalSeeder
        
        $absensi = [];
        
        // Generate absensi untuk 2 minggu terakhir
        for ($i = 0; $i < 14; $i++) {
            $tanggal = $tanggalMulai->copy()->addDays($i);
            $hariIni = strtolower($tanggal->locale('id')->dayName);
            
            // Skip weekend
            if ($hariIni == 'sabtu' || $hariIni == 'minggu') {
                continue;
            }
            
            // Untuk setiap siswa
            foreach ($siswaIds as $siswaId) {
                // Untuk setiap jadwal pada hari ini
                foreach ($jadwalIds as $jadwalId) {
                    // Cek apakah jadwal ini untuk hari ini (ini hanya simulasi, seharusnya query ke DB)
                    // Untuk contoh, kita asumsikan jadwal 1-3 untuk Senin-Rabu, jadwal 4-5 untuk Kamis-Jumat
                    $jadwalHari = '';
                    if ($jadwalId <= 3) {
                        if ($jadwalId == 1 || $jadwalId == 2) $jadwalHari = 'senin';
                        if ($jadwalId == 3) $jadwalHari = 'selasa';
                    } else {
                        if ($jadwalId == 4) $jadwalHari = 'selasa';
                        if ($jadwalId == 5) $jadwalHari = 'rabu';
                    }
                    
                    // Skip jika bukan jadwal untuk hari ini
                    if ($jadwalHari != $hariIni) {
                        continue;
                    }
                    
                    // Random status kehadiran (80% hadir, 10% sakit, 5% izin, 5% alpa)
                    $rand = rand(1, 100);
                    $status = 'hadir';
                    $catatan = null;
                    
                    if ($rand > 80 && $rand <= 90) {
                        $status = 'sakit';
                        $catatan = 'Sakit ' . ['flu', 'demam', 'batuk', 'pusing'][rand(0, 3)];
                    } else if ($rand > 90 && $rand <= 95) {
                        $status = 'izin';
                        $catatan = 'Izin ' . ['keluarga', 'acara', 'keperluan penting'][rand(0, 2)];
                    } else if ($rand > 95) {
                        $status = 'alpa';
                    }
                    
                    $absensi[] = [
                        'id_siswa' => $siswaId,
                        'id_jadwal' => $jadwalId,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'status' => $status,
                        'catatan' => $catatan,
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'seeder',
                        'diperbarui_pada' => now(),
                        'diperbarui_oleh' => 'seeder'
                    ];
                }
            }
        }
        
        // Insert batch absensi
        DB::table('absensi')->insert($absensi);
    }
}