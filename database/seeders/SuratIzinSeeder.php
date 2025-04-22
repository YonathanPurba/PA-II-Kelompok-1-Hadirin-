<?php

namespace Database\Seeders;

use App\Models\SuratIzin;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SuratIzinSeeder extends Seeder
{
    public function run()
    {
        // Ambil 10 siswa secara acak
        $siswa = Siswa::inRandomOrder()->limit(10)->get();
        
        $jenis = ['sakit', 'izin'];
        $alasan_sakit = [
            'Demam', 'Flu', 'Batuk', 'Sakit perut', 'Sakit kepala', 'Diare'
        ];
        $alasan_izin = [
            'Acara keluarga', 'Pernikahan saudara', 'Kematian kerabat', 'Perjalanan keluarga', 'Urusan penting'
        ];
        $status = ['menunggu', 'disetujui', 'ditolak'];
        
        foreach ($siswa as $s) {
            // Buat 1-3 surat izin per siswa
            $count = rand(1, 3);
            
            for ($i = 0; $i < $count; $i++) {
                $jenisIzin = $jenis[array_rand($jenis)];
                $alasan = $jenisIzin == 'sakit' ? 
                          $alasan_sakit[array_rand($alasan_sakit)] : 
                          $alasan_izin[array_rand($alasan_izin)];
                
                $tanggalMulai = Carbon::now()->subDays(rand(1, 30));
                $durasi = rand(1, 3); // 1-3 hari
                $tanggalSelesai = (clone $tanggalMulai)->addDays($durasi - 1);
                
                SuratIzin::create([
                    'id_siswa' => $s->id_siswa,
                    'id_orangtua' => $s->id_orangtua,
                    'jenis' => $jenisIzin,
                    'tanggal_mulai' => $tanggalMulai->format('Y-m-d'),
                    'tanggal_selesai' => $tanggalSelesai->format('Y-m-d'),
                    'alasan' => $alasan,
                    'file_lampiran' => null,
                    'status' => $status[array_rand($status)],
                    'dibuat_pada' => $tanggalMulai->subDays(1),
                    'dibuat_oleh' => 'orangtua' . $s->id_orangtua
                ]);
            }
        }
    }
}