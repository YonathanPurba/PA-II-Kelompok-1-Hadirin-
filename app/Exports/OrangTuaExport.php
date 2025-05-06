<?php

namespace App\Exports;

use App\Models\OrangTua;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrangTuaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $kelasId;
    protected $status;

    public function __construct($kelasId = null, $status = null)
    {
        $this->kelasId = $kelasId;
        $this->status = $status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = OrangTua::with(['siswa.kelas', 'user']);
        
        // Filter berdasarkan kelas anak
        if ($this->kelasId) {
            $query->whereHas('siswa', function ($siswaQuery) {
                $siswaQuery->where('id_kelas', $this->kelasId);
            });
        }
        
        // Status logic
        if ($this->status) {
            if ($this->status !== 'semua') {
                $query->where('status', $this->status);
            }
        } else {
            $query->where('status', 'aktif');
        }
        
        return $query->orderBy('nama_lengkap')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'Nomor Telepon',
            'Pekerjaan',
            'Alamat',
            'Nama Anak',
            'Status'
        ];
    }

    /**
     * @param mixed $orangTua
     * @return array
     */
    public function map($orangTua): array
    {
        static $no = 0;
        $no++;
        
        // Get children names
        $anakNames = '';
        if ($orangTua->siswa->count() > 0) {
            $anakNames = $orangTua->siswa->map(function($siswa) {
                $kelas = $siswa->kelas ? " ({$siswa->kelas->nama_kelas})" : '';
                return $siswa->nama . $kelas;
            })->join(', ');
        }

        return [
            $no,
            $orangTua->nama_lengkap,
            $orangTua->nomor_telepon ?? '-',
            $orangTua->pekerjaan ?? '-',
            $orangTua->alamat ?? '-',
            $anakNames ?: '-',
            ucfirst($orangTua->status)
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
            
            // Set column widths
            'A' => ['width' => 5],
            'B' => ['width' => 25],
            'C' => ['width' => 15],
            'D' => ['width' => 20],
            'E' => ['width' => 30],
            'F' => ['width' => 40],
            'G' => ['width' => 10],
        ];
    }
}