<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $tingkat = ['1', '2', '3', '4', '5', '6'];
        $kelas = ['A', 'B'];

        $id_guru = 1;
        foreach ($tingkat as $t) {
            foreach ($kelas as $k) {
                Kelas::create([
                    'nama_kelas' => $t . ' ' . $k,
                    'tingkat' => $t,
                    'id_guru' => $id_guru,
                    'id_tahun_ajaran' => 1,
                    'dibuat_pada' => now(),
                    'dibuat_oleh' => 'system'
                ]);
                
                $id_guru++;
                if ($id_guru > 10) {
                    $id_guru = 1; // Reset jika melebihi jumlah guru
                }
            }
        }
    }
}