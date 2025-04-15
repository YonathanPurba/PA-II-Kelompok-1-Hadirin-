<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SiswaExport implements FromView
{
    protected $kelas;

    public function __construct($kelas = null)
    {
        $this->kelas = $kelas;
    }

    public function view(): View
    {
        $siswaList = Siswa::when($this->kelas, function ($query, $kelas) {
            return $query->where('id_kelas', $kelas);
        })->get();

        return view('exports.siswa_excel', compact('siswaList'));
    }
}
