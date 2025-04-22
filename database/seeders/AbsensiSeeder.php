<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua siswa
        $siswa = Siswa::all();
        
        // Ambil jadwal untuk kelas masing-masing siswa
        foreach ($siswa as $s) {
            $jadwal = Jadwal::where('id_kelas', $s->id_kelas)->get();
            
            // Buat absensi untuk 2 minggu terakhir
            $startDate = Carbon::now()->subWeeks(2)->startOfWeek();
            $endDate = Carbon::now()->subDay();
            
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $dayName = strtolower($date->format('l'));
                if ($dayName == 'saturday' || $dayName == 'sunday') {
                    continue; // Lewati hari Sabtu dan Minggu
                }
                
                // Konversi nama hari ke bahasa Indonesia
                $hariIndonesia = [
                    'monday' => 'senin',
                    'tuesday' => 'selasa',
                    'wednesday' => 'rabu',
                    'thursday' => 'kamis',
                    'friday' => 'jumat'
                ];
                
                $hariId = $hariIndonesia[$dayName];
                
                // Ambil jadwal untuk hari ini
                $jadwalHariIni = $jadwal->where('hari', $hariId);
                
                foreach ($jadwalHariIni as $j) {
                    // Status kehadiran acak
                    $status = ['hadir', 'hadir', 'hadir', 'hadir', 'hadir', 'sakit', 'izin', 'alpa'];
                    $randomStatus = $status[array_rand($status)];
                    
                    // Catatan jika tidak hadir
                    $catatan = null;
                    if ($randomStatus != 'hadir') {
                        $catatan = $randomStatus == 'sakit' ? 'Siswa sakit' : 
                                  ($randomStatus == 'izin' ? 'Izin keluarga' : 'Tidak ada keterangan');
                    }
                    
                    Absensi::create([
                        'id_siswa' => $s->id_siswa,
                        'id_jadwal' => $j->id_jadwal,
                        'tanggal' => $date->format('Y-m-d'),
                        'status' => $randomStatus,
                        'catatan' => $catatan,
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'system'
                    ]);
                }
            }
        }
    }
}