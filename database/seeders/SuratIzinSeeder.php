<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuratIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suratIzin = [
            [
                'id_siswa' => 1, // Andi
                'id_orangtua' => 1, // Ahmad Wijaya
                'jenis' => 'sakit',
                'tanggal_mulai' => Carbon::now()->subDays(5),
                'tanggal_selesai' => Carbon::now()->subDays(3),
                'alasan' => 'Sakit demam dan flu',
                'file_lampiran' => null,
                'status' => 'disetujui',
                'dibuat_pada' => Carbon::now()->subDays(6),
                'dibuat_oleh' => 'orangtua1',
                'diperbarui_pada' => Carbon::now()->subDays(5),
                'diperbarui_oleh' => 'admin'
            ],
            [
                'id_siswa' => 3, // Citra
                'id_orangtua' => 2, // Dewi Susanti
                'jenis' => 'izin',
                'tanggal_mulai' => Carbon::now()->subDays(2),
                'tanggal_selesai' => Carbon::now()->subDays(2),
                'alasan' => 'Acara keluarga',
                'file_lampiran' => null,
                'status' => 'disetujui',
                'dibuat_pada' => Carbon::now()->subDays(3),
                'dibuat_oleh' => 'orangtua2',
                'diperbarui_pada' => Carbon::now()->subDays(3),
                'diperbarui_oleh' => 'admin'
            ],
            [
                'id_siswa' => 2, // Budi
                'id_orangtua' => 1, // Ahmad Wijaya
                'jenis' => 'sakit',
                'tanggal_mulai' => Carbon::now(),
                'tanggal_selesai' => Carbon::now()->addDays(2),
                'alasan' => 'Sakit perut',
                'file_lampiran' => null,
                'status' => 'menunggu',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'orangtua1',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'orangtua1'
            ],
        ];

        DB::table('surat_izin')->insert($suratIzin);
    }
}