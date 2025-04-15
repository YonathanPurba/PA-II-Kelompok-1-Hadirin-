<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil tahun ajaran terakhir atau sebelumnya
        $tahunAjaran = DB::table('tahun_ajaran')
            ->orderByDesc('tanggal_mulai')
            ->skip(1) // Lewati tahun ajaran terbaru
            ->first();

        if (!$tahunAjaran) {
            $this->command->warn('Tidak ada tahun ajaran sebelumnya ditemukan.');
            return;
        }

        $siswaData = [];

        for ($i = 1; $i <= 20; $i++) {
            $siswaData[] = [
                'nama' => 'Siswa ' . $i,
                'nis' => str_pad(10000 + $i, 5, '0', STR_PAD_LEFT),
                'id_orangtua' => (($i - 1) % 5) + 1,
                'id_kelas' => ($i % 2) + 1,
                'id_tahun_ajaran' => $tahunAjaran->id_tahun_ajaran, // Perbaikan disini
                'tempat_lahir' => 'Kota ' . (($i % 3) + 1),
                'tanggal_lahir' => Carbon::parse('2010-01-01')->addDays($i * 30),
                'alamat' => 'Jalan Contoh No. ' . $i,
                'jenis_kelamin' => $i % 2 === 0 ? 'perempuan' : 'laki-laki',
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'Seeder',
            ];
        }

        DB::table('siswa')->insert($siswaData);
    }
}
