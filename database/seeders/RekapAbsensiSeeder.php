<?php

namespace Database\Seeders;

use App\Models\RekapAbsensi;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RekapAbsensiSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua siswa
        $siswa = Siswa::all();
        
        // Buat rekap untuk bulan lalu
        $bulanLalu = Carbon::now()->subMonth();
        $bulan = $bulanLalu->format('m');
        $tahun = $bulanLalu->format('Y');
        
        foreach ($siswa as $s) {
            // Hitung jumlah kehadiran dari data absensi
            $absensi = Absensi::where('id_siswa', $s->id_siswa)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();
            
            $jumlahHadir = $absensi->where('status', 'hadir')->count();
            $jumlahSakit = $absensi->where('status', 'sakit')->count();
            $jumlahIzin = $absensi->where('status', 'izin')->count();
            $jumlahAlpa = $absensi->where('status', 'alpa')->count();
            
            RekapAbsensi::create([
                'id_siswa' => $s->id_siswa,
                'id_kelas' => $s->id_kelas,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah_hadir' => $jumlahHadir,
                'jumlah_sakit' => $jumlahSakit,
                'jumlah_izin' => $jumlahIzin,
                'jumlah_alpa' => $jumlahAlpa,
                'dibuat_pada' => Carbon::now()->startOfMonth(),
                'dibuat_oleh' => 'system'
            ]);
        }
    }
}