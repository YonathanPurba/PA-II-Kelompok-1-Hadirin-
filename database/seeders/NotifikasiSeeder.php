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
        $notifikasi = [
            // Notifikasi untuk admin
            [
                'id_user' => 1, // admin
                'judul' => 'Surat Izin Baru',
                'pesan' => 'Ada surat izin baru yang perlu disetujui',
                'tipe' => 'info',
                'dibaca' => false,
                'waktu_dibaca' => null,
                'dibuat_pada' => Carbon::now()->subHours(1),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now()->subHours(1),
                'diperbarui_oleh' => 'system'
            ],
            
            // Notifikasi untuk guru
            [
                'id_user' => 2, // guru1
                'judul' => 'Jadwal Mengajar Hari Ini',
                'pesan' => 'Anda memiliki jadwal mengajar Matematika di kelas 7A pukul 07:30',
                'tipe' => 'info',
                'dibaca' => true,
                'waktu_dibaca' => Carbon::now()->subHours(3),
                'dibuat_pada' => Carbon::now()->subHours(12),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now()->subHours(3),
                'diperbarui_oleh' => 'system'
            ],
            
            // Notifikasi untuk orangtua
            [
                'id_user' => 4, // orangtua1
                'judul' => 'Surat Izin Disetujui',
                'pesan' => 'Surat izin untuk Andi telah disetujui',
                'tipe' => 'success',
                'dibaca' => false,
                'waktu_dibaca' => null,
                'dibuat_pada' => Carbon::now()->subDays(5),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now()->subDays(5),
                'diperbarui_oleh' => 'system'
            ],
            [
                'id_user' => 5, // orangtua2
                'judul' => 'Surat Izin Disetujui',
                'pesan' => 'Surat izin untuk Citra telah disetujui',
                'tipe' => 'success',
                'dibaca' => true,
                'waktu_dibaca' => Carbon::now()->subDays(2),
                'dibuat_pada' => Carbon::now()->subDays(3),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now()->subDays(2),
                'diperbarui_oleh' => 'system'
            ],
        ];

        DB::table('notifikasi')->insert($notifikasi);
    }
}