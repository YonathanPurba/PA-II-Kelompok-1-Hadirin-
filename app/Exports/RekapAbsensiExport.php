<?php

namespace App\Exports;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\RekapAbsensi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapAbsensiExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    protected $id_kelas;
    protected $bulan;
    protected $tahun;
    protected $namaBulan;

    public function __construct($id_kelas, $bulan, $tahun)
    {
        $this->id_kelas = $id_kelas;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        
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
        
        $this->namaBulan = $bulanList[$bulan];
    }

    public function collection()
    {
        $kelas = Kelas::findOrFail($this->id_kelas);
        $siswaList = Siswa::where('id_kelas', $this->id_kelas)->orderBy('nama')->get();
        
        // Get attendance data
        $startDate = Carbon::createFromDate($this->tahun, $this->bulan, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($this->tahun, $this->bulan, 1)->endOfMonth();
        
        $absensiData = DB::table('absensi')
            ->join('siswa', 'absensi.id_siswa', '=', 'siswa.id_siswa')
            ->where('siswa.id_kelas', $this->id_kelas)
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
            
        $data = collect();
        
        foreach ($siswaList as $index => $siswa) {
            $rekapSiswa = $absensiData[$siswa->id_siswa] ?? null;
            
            $data->push([
                'No' => $index + 1,
                'Nama Siswa' => $siswa->nama,
                'Hadir' => $rekapSiswa ? $rekapSiswa->jumlah_hadir : 0,
                'Sakit' => $rekapSiswa ? $rekapSiswa->jumlah_sakit : 0,
                'Izin' => $rekapSiswa ? $rekapSiswa->jumlah_izin : 0,
                'Alpa' => $rekapSiswa ? $rekapSiswa->jumlah_alpa : 0,
                'Total' => $rekapSiswa ? 
                    ($rekapSiswa->jumlah_hadir + $rekapSiswa->jumlah_sakit + $rekapSiswa->jumlah_izin + $rekapSiswa->jumlah_alpa) : 0
            ]);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Hadir',
            'Sakit',
            'Izin',
            'Alpa',
            'Total'
        ];
    }

    public function title(): string
    {
        $kelas = Kelas::findOrFail($this->id_kelas);
        return 'Rekap Absensi ' . $kelas->nama_kelas . ' - ' . $this->namaBulan . ' ' . $this->tahun;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
