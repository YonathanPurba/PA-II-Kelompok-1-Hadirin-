<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\RekapAbsensi;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RekapitulasiController extends Controller
{
    public function index()
{
    // Ambil tahun ajaran yang aktif
    $tahunAjaranAktif = TahunAjaran::where('aktif', true)->first();

    // Jika tidak ada tahun ajaran aktif, kembalikan view dengan kelas kosong
    if (!$tahunAjaranAktif) {
        $kelasList = collect(); // kosong
    } else {
        // Ambil kelas yang berada dalam tahun ajaran aktif
        $kelasList = Kelas::with(['guru', 'tahunAjaran', 'siswa'])
            ->where('id_tahun_ajaran', $tahunAjaranAktif->id_tahun_ajaran)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();
    }

    return view('admin.pages.rekapitulasi.rekapitulasi', compact('kelasList'));
}


    public function showByKelas(Request $request, $id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);
        $tahunAjaran = $kelas->tahunAjaran;
        
        // Get all students in the class
        $siswaList = Siswa::where('id_kelas', $id_kelas)->orderBy('nama')->get();
        
        // Get selected month and year or default to current
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->format('Y'));
        
        // Get all months for the dropdown
        $bulanList = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        
        // Get years for dropdown (current year and 2 years back)
        $tahunList = [];
        $currentYear = (int)Carbon::now()->format('Y');
        for ($i = $currentYear - 2; $i <= $currentYear; $i++) {
            $tahunList[$i] = $i;
        }
        
        // Get attendance data for the selected month and year
        $rekapData = $this->getRekapData($id_kelas, $bulan, $tahun);
        
        return view('admin.pages.rekapitulasi.kelas-rekapitulasi', compact(
            'kelas', 
            'siswaList', 
            'bulan', 
            'tahun', 
            'bulanList', 
            'tahunList', 
            'rekapData',
            'tahunAjaran'
        ));
    }
    
    private function getRekapData($id_kelas, $bulan, $tahun)
    {
        // First check if we have summary data in rekap_absensi table
        $rekapData = RekapAbsensi::where('id_kelas', $id_kelas)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get()
            ->keyBy('id_siswa');
            
        // If we don't have summary data, calculate it from the absensi table
        if ($rekapData->isEmpty()) {
            $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
            
            $absensiData = DB::table('absensi')
                ->join('siswa', 'absensi.id_siswa', '=', 'siswa.id_siswa')
                ->where('siswa.id_kelas', $id_kelas)
                ->whereBetween('absensi.tanggal', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->select(
                    'siswa.id_siswa',
                    DB::raw('SUM(CASE WHEN absensi.status = "hadir" THEN 1 ELSE 0 END) as jumlah_hadir'),
                    DB::raw('SUM(CASE WHEN absensi.status = "sakit" THEN 1 ELSE 0 END) as jumlah_sakit'),
                    DB::raw('SUM(CASE WHEN absensi.status = "izin" THEN 1 ELSE 0 END) as jumlah_izin'),
                    DB::raw('SUM(CASE WHEN absensi.status = "alpa" THEN 1 ELSE 0 END) as jumlah_alpa')
                )
                ->groupBy('siswa.id_siswa')
                ->get()
                ->keyBy('id_siswa');
                
            return $absensiData;
        }
        
        return $rekapData;
    }
    
    public function exportPdf(Request $request)
    {
        $id_kelas = $request->kelas;
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->format('Y'));
        
        $kelas = Kelas::findOrFail($id_kelas);
        $siswaList = Siswa::where('id_kelas', $id_kelas)->orderBy('nama')->get();
        $rekapData = $this->getRekapData($id_kelas, $bulan, $tahun);
        
        $bulanList = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        
        $namaBulan = $bulanList[$bulan];
        
        $pdf = Pdf::loadView('admin.pages.rekapitulasi.pdf', compact(
            'kelas', 
            'siswaList', 
            'rekapData', 
            'bulan', 
            'tahun', 
            'namaBulan'
        ));
        
        return $pdf->download('rekap-absensi-' . $kelas->nama_kelas . '-' . $namaBulan . '-' . $tahun . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $id_kelas = $request->kelas;
        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->format('Y'));
        
        $kelas = Kelas::findOrFail($id_kelas);
        $bulanList = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        
        $namaBulan = $bulanList[$bulan];
        
        return Excel::download(new \App\Exports\RekapAbsensiExport($id_kelas, $bulan, $tahun), 
            'rekap-absensi-' . $kelas->nama_kelas . '-' . $namaBulan . '-' . $tahun . '.xlsx');
    }
}
