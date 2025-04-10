<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $statusList = ['hadir', 'alpa', 'sakit', 'izin'];
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2025, 6, 30);
        $dates = [];

        // Ambil semua tanggal antara Januari - Juni
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->toDateString();
        }

        $data = [];

        foreach (range(1, 20) as $id_siswa) {
            foreach (range(1, 25) as $id_jadwal) {
                // Ambil 2 tanggal unik secara acak per siswa-jadwal
                $randomDates = collect($dates)->random(2);
                foreach ($randomDates as $tanggal) {
                    $data[] = [
                        'id_siswa' => $id_siswa,
                        'id_jadwal' => $id_jadwal,
                        'tanggal' => $tanggal,
                        'status' => $statusList[array_rand($statusList)],
                        'catatan' => null,
                        'dibuat_pada' => now(),
                        'dibuat_oleh' => 'Seeder',
                        'diperbarui_pada' => now(),
                        'diperbarui_oleh' => 'Seeder',
                    ];
                }
            }
        }

        DB::table('absensi')->insert($data); // Total: 20 siswa × 25 jadwal × 2 tanggal = 1000 record
    }
}
