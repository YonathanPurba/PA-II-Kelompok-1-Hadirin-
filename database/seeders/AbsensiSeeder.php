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
        $absensi = [];
        $id = 1;
        
        // Get all students
        $allSiswa = DB::table('siswa')->get();
        
        // Get all schedules
        $allJadwal = DB::table('jadwal')->get();
        
        // Group schedules by class
        $jadwalByKelas = [];
        foreach ($allJadwal as $jadwal) {
            if (!isset($jadwalByKelas[$jadwal->id_kelas])) {
                $jadwalByKelas[$jadwal->id_kelas] = [];
            }
            $jadwalByKelas[$jadwal->id_kelas][] = $jadwal;
        }
        
        // Generate attendance for April and May 2025
        $months = [4, 5]; // April and May
        $year = 2025;
        
        foreach ($months as $month) {
            $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
            
            // For each day in the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::createFromDate($year, $month, $day);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }
                
                $dayName = strtolower($date->locale('id')->dayName);
                $dayNameIndonesian = [
                    'monday' => 'senin',
                    'tuesday' => 'selasa',
                    'wednesday' => 'rabu',
                    'thursday' => 'kamis',
                    'friday' => 'jumat',
                ][$dayName] ?? $dayName;
                
                // For each student
                foreach ($allSiswa as $siswa) {
                    // Get schedules for this student's class and day
                    $jadwalHariIni = array_filter($jadwalByKelas[$siswa->id_kelas] ?? [], function($j) use ($dayNameIndonesian) {
                        return $j->hari === $dayNameIndonesian;
                    });
                    
                    if (empty($jadwalHariIni)) {
                        continue;
                    }
                    
                    foreach ($jadwalHariIni as $jadwal) {
                        // Determine attendance status (mostly present, sometimes absent)
                        $randomStatus = rand(1, 100);
                        if ($randomStatus <= 90) {
                            $status = 'hadir';
                        } elseif ($randomStatus <= 95) {
                            $status = 'sakit';
                        } elseif ($randomStatus <= 98) {
                            $status = 'izin';
                        } else {
                            $status = 'alpa';
                        }
                        
                        $absensi[] = [
                            'id_absensi' => $id++,
                            'id_siswa' => $siswa->id_siswa,
                            'id_jadwal' => $jadwal->id_jadwal,
                            'tanggal' => $date->format('Y-m-d'),
                            'status' => $status,
                            'catatan' => $status == 'hadir' ? null : 'Catatan untuk ' . $status,
                            'dibuat_pada' => Carbon::now(),
                            'dibuat_oleh' => 'system',
                            'diperbarui_pada' => Carbon::now(),
                            'diperbarui_oleh' => 'system'
                        ];
                        
                        // To prevent the data from being too large, we'll limit the number of records
                        if ($id > 50000) {
                            break 4; // Break out of all loops
                        }
                    }
                }
            }
        }
        
        // Insert in chunks to avoid memory issues
        foreach (array_chunk($absensi, 1000) as $chunk) {
            DB::table('absensi')->insert($chunk);
        }
    }
}