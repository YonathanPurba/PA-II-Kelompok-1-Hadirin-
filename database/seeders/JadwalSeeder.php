<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run()
    {
<<<<<<< Updated upstream
        $hari = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $waktu = [
            ['07:30:00', '09:00:00'],
            ['09:15:00', '10:45:00'],
            ['11:00:00', '12:30:00'],
            ['13:30:00', '15:00:00']
        ];

        // Buat jadwal untuk setiap kelas
        for ($kelas_id = 1; $kelas_id <= 12; $kelas_id++) {
            // Untuk setiap hari
            foreach ($hari as $h) {
                // Untuk setiap slot waktu
                foreach ($waktu as $index => $w) {
                    // Pilih mata pelajaran dan guru secara acak
                    $mata_pelajaran_id = rand(1, 8);
                    
                    // Cari guru yang mengajar mata pelajaran tersebut
                    $guru_id = null;
                    if ($mata_pelajaran_id == 1) { // Matematika
                        $guru_id = rand(0, 1) ? 1 : 9;
                    } elseif ($mata_pelajaran_id == 4) { // IPA
                        $guru_id = rand(0, 1) ? 4 : 10;
                    } else {
                        $guru_id = $mata_pelajaran_id;
                    }
                    
                    // Buat jadwal
                    Jadwal::create([
                        'id_kelas' => $kelas_id,
                        'id_mata_pelajaran' => $mata_pelajaran_id,
                        'id_guru' => $guru_id,
                        'hari' => $h,
                        'waktu_mulai' => $w[0],
                        'waktu_selesai' => $w[1],
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'system'
                    ]);
                }
            }
        }
=======
        // Daftar hari, kelas, guru, dan mata pelajaran
        $hariList = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $idKelas = [1, 2];
        $idGuru = [1, 2, 3, 4]; // Menambahkan ID guru baru
        $idMapel = [1, 2, 3]; // Misalnya 3 mata pelajaran

        // Waktu mulai untuk jadwal
        $waktuMulai = Carbon::createFromTime(7, 30);
        $durasi = 90; // Durasi setiap pelajaran dalam menit

        $data = [];

        // Loop untuk setiap hari, kelas, dan mata pelajaran
        foreach ($hariList as $hari) {
            foreach ($idKelas as $kelas) {
                foreach ($idMapel as $mapel) {
                    // Pilih secara acak guru untuk setiap mata pelajaran
                    $guru = $idGuru[array_rand($idGuru)];
                    $mulai = $waktuMulai->copy();
                    $selesai = $mulai->copy()->addMinutes($durasi);

                    // Tambahkan data jadwal ke array
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

                    // Geser waktu mulai ke jam berikutnya untuk jadwal berikutnya
                    $waktuMulai->addMinutes($durasi);
                }

                // Reset waktu mulai setelah setiap kelas selesai
                $waktuMulai = Carbon::createFromTime(7, 30);
            }
        }

        // Masukkan data jadwal ke dalam tabel 'jadwal'
        DB::table('jadwal')->insert($data);
>>>>>>> Stashed changes
    }
}