<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $idKelas = [1, 2];
        $idGuru = [1, 2];
        $idMapel = [1, 2, 3];

        $waktuMulai = Carbon::createFromTime(7, 30);
        $durasi = 90; // menit

        $data = [];

        foreach ($hariList as $hari) {
            foreach ($idKelas as $kelas) {
                foreach ($idMapel as $mapel) {
                    $guru = $idGuru[array_rand($idGuru)];
                    $mulai = $waktuMulai->copy();
                    $selesai = $mulai->copy()->addMinutes($durasi);

                    $data[] = [
                        'id_kelas' => $kelas,
                        'id_mata_pelajaran' => $mapel,
                        'id_guru' => $guru,
                        'hari' => $hari,
                        'waktu_mulai' => $mulai->format('H:i:s'),
                        'waktu_selesai' => $selesai->format('H:i:s'),
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'Seeder',
                        'diperbarui_pada' => null,
                        'diperbarui_oleh' => null,
                    ];

                    // Geser waktu mulai ke jam berikutnya
                    $waktuMulai->addMinutes($durasi);
                }

                // Reset waktu mulai untuk kelas berikutnya
                $waktuMulai = Carbon::createFromTime(7, 30);
            }
        }

        DB::table('jadwal')->insert($data);
    }
}
