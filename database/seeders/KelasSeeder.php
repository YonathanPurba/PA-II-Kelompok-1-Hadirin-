<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [];
        $id = 1;
        
        $tingkat = ['7', '8', '9'];
        $nama = ['A', 'B', 'C', 'D'];
        
        // Get active tahun ajaran
        $tahunAjaranAktif = DB::table('tahun_ajaran')->where('aktif', 1)->first();
        $idTahunAjaran = $tahunAjaranAktif ? $tahunAjaranAktif->id_tahun_ajaran : 1;
        
        foreach ($tingkat as $t) {
            foreach ($nama as $n) {
                $walikelas = rand(1, 30); // Random teacher as homeroom teacher
                
                $kelas[] = [
                    'id_kelas' => $id++,
                    'nama_kelas' => $t . $n,
                    'tingkat' => $t,
                    'id_guru' => $walikelas,
                    'id_tahun_ajaran' => $idTahunAjaran,
                    'dibuat_pada' => Carbon::now(),
                    'dibuat_oleh' => 'system',
                    'diperbarui_pada' => Carbon::now(),
                    'diperbarui_oleh' => 'system'
                ];
            }
        }
        
        DB::table('kelas')->insert($kelas);
    }
}