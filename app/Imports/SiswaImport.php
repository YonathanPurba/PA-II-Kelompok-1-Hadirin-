<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Ambil tahun ajaran aktif terakhir (ganti logika jika dibutuhkan)
        $tahunAjaran = DB::table('tahun_ajaran')
            ->orderByDesc('tanggal_mulai')
            ->first();

        return new Siswa([
            'nama' => $row['nama_siswa'],
            'nis' => $row['nisn'],
            'id_orangtua' => $row['orang_tua_siswa'],
            'id_kelas' => $row['kelas_siswa'],
            'id_tahun_ajaran' => $tahunAjaran ? $tahunAjaran->id_tahun_ajaran : null,
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d'),
            'jenis_kelamin' => $row['jenis_kelamin'],
            'alamat' => $row['alamat'],
            'dibuat_pada' => Carbon::now(),
            'dibuat_oleh' => 'import_excel',
            'diperbarui_pada' => null,
            'diperbarui_oleh' => null,
        ]);
    }
}
