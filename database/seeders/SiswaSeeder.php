<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswaData = [];

        for ($i = 1; $i <= 20; $i++) {
            $siswaData[] = [
                'nama' => 'Siswa ' . $i,
                'nis' => str_pad(10000 + $i, 5, '0', STR_PAD_LEFT),
                'id_orangtua' => (($i - 1) % 5) + 1, // Hanya id_orangtua 1 s/d 5
                'id_kelas' => ($i % 2) + 1, // Misal ada 2 kelas: 1 dan 2
                'tanggal_lahir' => Carbon::parse('2010-01-01')->addDays($i * 30),
                'jenis_kelamin' => $i % 2 === 0 ? 'perempuan' : 'laki-laki',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'Seeder',
            ];
        }

        DB::table('siswa')->insert($siswaData);
    }
}
