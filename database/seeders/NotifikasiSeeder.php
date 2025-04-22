<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NotifikasiSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        
        $tipe = ['info', 'warning', 'success', 'error'];
        $judul = [
            'Pengumuman Penting',
            'Jadwal Ujian',
            'Libur Sekolah',
            'Rapat Orangtua',
            'Kegiatan Ekstrakurikuler',
            'Pembayaran SPP',
            'Perubahan Jadwal'
        ];
        
        foreach ($users as $user) {
            // Buat 3-7 notifikasi per user
            $count = rand(3, 7);
            
            for ($i = 0; $i < $count; $i++) {
                $judulNotif = $judul[array_rand($judul)];
                $tipeNotif = $tipe[array_rand($tipe)];
                $dibuat = Carbon::now()->subDays(rand(1, 30));
                
                // 70% notifikasi belum dibaca
                $dibaca = rand(1, 10) > 7;
                $waktuDibaca = $dibaca ? $dibuat->copy()->addHours(rand(1, 24)) : null;
                
                Notifikasi::create([
                    'id_user' => $user->id_user,
                    'judul' => $judulNotif,
                    'pesan' => 'Ini adalah notifikasi ' . strtolower($judulNotif) . ' untuk ' . $user->username . '.',
                    'tipe' => $tipeNotif,
                    'dibaca' => $dibaca,
                    'waktu_dibaca' => $waktuDibaca,
                    'dibuat_pada' => $dibuat,
                    'dibuat_oleh' => 'system'
                ]);
            }
        }
    }
}