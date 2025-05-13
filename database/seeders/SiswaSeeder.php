<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = [];
        $id = 1;
        
        // Get active tahun ajaran
        $tahunAjaranAktif = DB::table('tahun_ajaran')->where('aktif', 1)->first();
        $idTahunAjaran = $tahunAjaranAktif ? $tahunAjaranAktif->id_tahun_ajaran : 1;
        
        // Generate 25 students for each class (12 classes total)
        for ($idKelas = 1; $idKelas <= 12; $idKelas++) {
            $kelasInfo = DB::table('kelas')->where('id_kelas', $idKelas)->first();
            
            for ($i = 1; $i <= 25; $i++) {
                $gender = ($i % 2 == 0) ? 'laki-laki' : 'perempuan';
                $birthYear = 2025 - (12 + intval($kelasInfo->tingkat));
                
                $siswa[] = [
                    'id_siswa' => $id,
                    'nama' => ($gender == 'laki-laki' ? 'Siswa Laki-laki ' : 'Siswa Perempuan ') . $id,
                    'nis' => $kelasInfo->tingkat . str_pad($idKelas, 2, '0', STR_PAD_LEFT) . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'id_orangtua' => min($id, 100), // Max 100 parents
                    'id_kelas' => $idKelas,
                    'id_tahun_ajaran' => $idTahunAjaran,
                    'tempat_lahir' => 'Kota ' . chr(64 + ($id % 26) + 1),
                    'tanggal_lahir' => Carbon::createFromDate($birthYear, ($id % 12) + 1, ($id % 28) + 1)->format('Y-m-d'),
                    'jenis_kelamin' => $gender,
                    'alamat' => 'Jalan Siswa No. ' . $id,
                    'status' => 'aktif',
                    'dibuat_pada' => Carbon::now(),
                    'dibuat_oleh' => 'system',
                    'diperbarui_pada' => Carbon::now(),
                    'diperbarui_oleh' => 'system'
                ];
                
                $id++;
            }
        }
        
        DB::table('siswa')->insert($siswa);
    }
}