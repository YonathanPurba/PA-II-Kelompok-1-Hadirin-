<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifikasi = [];
        $id = 1;
        
        // Get all users
        $allUsers = DB::table('users')->get();
        
        $jenisNotifikasi = [
            'Absensi' => 'Notifikasi terkait absensi siswa',
            'Surat Izin' => 'Notifikasi terkait surat izin',
            'Pengumuman' => 'Pengumuman penting dari sekolah',
            'Jadwal' => 'Perubahan jadwal pelajaran',
            'Nilai' => 'Notifikasi terkait nilai siswa',
            'Kegiatan' => 'Informasi kegiatan sekolah',
            'Tagihan' => 'Informasi tagihan pembayaran',
            'Lainnya' => 'Notifikasi lainnya'
        ];
        
        // Generate notifications for April and May 2025
        $months = [4, 5]; // April and May
        $year = 2025;
        
        // For each user, create 5-10 notifications
        foreach ($allUsers as $user) {
            $numNotifications = rand(5, 10);
            
            for ($i = 0; $i < $numNotifications; $i++) {
                $month = $months[array_rand($months)];
                $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
                $day = rand(1, $daysInMonth);
                $date = Carbon::createFromDate($year, $month, $day);
                
                $jenis = array_rand($jenisNotifikasi);
                $pesan = $jenisNotifikasi[$jenis];
                
                $notifikasi[] = [
                    'id_notifikasi' => $id++,
                    'id_user' => $user->id_user,
                    'judul' => $jenis . ' - ' . substr(md5(rand()), 0, 8),
                    'pesan' => $pesan . ' - ' . substr(md5(rand()), 0, 16),
                    'tipe' => ['info', 'warning', 'success', 'danger'][rand(0, 3)],
                    'dibaca' => rand(0, 1),
                    'waktu_dibaca' => rand(0, 1) ? Carbon::now() : null,
                    'dibuat_pada' => Carbon::now(),
                    'dibuat_oleh' => 'system',
                    'diperbarui_pada' => Carbon::now(),
                    'diperbarui_oleh' => 'system'
                ];
            }
        }
        
        // Insert in chunks to avoid memory issues
        foreach (array_chunk($notifikasi, 1000) as $chunk) {
            DB::table('notifikasi')->insert($chunk);
        }
    }
}