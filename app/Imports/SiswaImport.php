<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Siswa([
            'nama' => $row['nama_siswa'],
            'nis' => $row['nisn'],
            'id_orangtua' => $row['orang_tua_siswa'],
            'id_kelas' => $row['kelas_siswa'],
            'tanggal_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d'),
            'jenis_kelamin' => $row['jenis_kelamin'],
        ]);
    }
}
