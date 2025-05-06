<?php

namespace App\Exports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GuruExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $mataPelajaranId;
    protected $status;

    public function __construct($mataPelajaranId = null, $status = null)
    {
        $this->mataPelajaranId = $mataPelajaranId;
        $this->status = $status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Guru::with(['user', 'mataPelajaran', 'jadwal'])
            ->when($this->mataPelajaranId, function ($query) {
                return $query->whereHas('mataPelajaran', function ($q) {
                    $q->where('id_mata_pelajaran', $this->mataPelajaranId);
                });
            })
            ->when($this->status, function ($query) {
                if ($this->status !== 'semua') {
                    return $query->where('status', $this->status);
                }
            }, function ($query) {
                return $query->where('status', 'aktif');
            })
            ->orderBy('nama_lengkap')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'NIP',
            'Nomor Telepon',
            'Bidang Studi',
            'Mata Pelajaran',
            'Status',
            'Jumlah Jadwal'
        ];
    }

    /**
     * @param mixed $guru
     * @return array
     */
    public function map($guru): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $guru->nama_lengkap,
            $guru->nip ?? '-',
            $guru->nomor_telepon ?? '-',
            $guru->bidang_studi ?? '-',
            $guru->mataPelajaran->pluck('nama')->join(', ') ?: '-',
            ucfirst($guru->status),
            $guru->jadwal->count()
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
            'B' => ['width' => 30],
            'C' => ['width' => 15],
            'D' => ['width' => 15],
            'E' => ['width' => 20],
            'F' => ['width' => 30],
            'G' => ['width' => 10],
            'H' => ['width' => 15],
        ];
    }
}