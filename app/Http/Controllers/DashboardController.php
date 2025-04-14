<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahGuru = Guru::count();
        $jumlahSiswa = Siswa::count();
        $jumlahKelas = Kelas::count();
        $detailKelas = Kelas::with('guru')->get();

        $totalHadir = Absensi::where('status', 'hadir')->count();
        $totalAlpa = Absensi::where('status', 'alpa')->count();
        $totalSakit = Absensi::where('status', 'sakit')->count();
        $totalIzin = Absensi::where('status', 'izin')->count();

        $guru = Guru::with('mataPelajaran')->get();        

        // Ganti jadi minggu ini
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString(); // mulai hari Senin
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY)->toDateString();     // sampai Minggu

        $absensiPerHari = Absensi::selectRaw("
        DATE(tanggal) as tanggal,
        SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
        SUM(CASE WHEN status = 'alpa' THEN 1 ELSE 0 END) as alpa,
        SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit,
        SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin")
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalMingguIni = Absensi::selectRaw("
        SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
        SUM(CASE WHEN status = 'alpa' THEN 1 ELSE 0 END) as alpa,
        SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit,
        SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin")
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->first();

        $absensiPerKelasMingguIni = Absensi::selectRaw("
        kelas.nama_kelas,
        SUM(CASE WHEN absensi.status = 'hadir' THEN 1 ELSE 0 END) as hadir,
        SUM(CASE WHEN absensi.status = 'alpa' THEN 1 ELSE 0 END) as alpa,
        SUM(CASE WHEN absensi.status = 'sakit' THEN 1 ELSE 0 END) as sakit,
        SUM(CASE WHEN absensi.status = 'izin' THEN 1 ELSE 0 END) as izin")
            ->join('jadwal', 'absensi.id_jadwal', '=', 'jadwal.id_jadwal')
            ->join('kelas', 'jadwal.id_kelas', '=', 'kelas.id_kelas')
            ->whereBetween('absensi.tanggal', [$startOfWeek, $endOfWeek])
            ->groupBy('kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas', 'asc')
            ->get();


        return view('admin.pages.beranda', compact(
            'jumlahGuru',
            'jumlahSiswa',
            'jumlahKelas',
            'totalHadir',
            'totalAlpa',
            'totalSakit',
            'totalIzin',
            'guru',
            'detailKelas',
            'absensiPerHari',
            'totalMingguIni',
            'absensiPerKelasMingguIni',
        ));
    }
}
