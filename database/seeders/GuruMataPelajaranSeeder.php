<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GuruMataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guruMataPelajaran = [];
        $id = 1;
        
        // Each teacher can teach 2-3 subjects
        for ($idGuru = 1; $idGuru <= 30; $idGuru++) {
            $numSubjects = rand(2, 3);
            $subjects = array_rand(range(1, 20), $numSubjects);
            
            if (!is_array($subjects)) {
                $subjects = [$subjects];
            }
            
            foreach ($subjects as $subject) {
                $guruMataPelajaran[] = [
                    'id_guru_mata_pelajaran' => $id++,
                    'id_guru' => $idGuru,
                    'id_mata_pelajaran' => $subject + 1,
                    'dibuat_pada' => Carbon::now(),
                    'dibuat_oleh' => 'system',
                    'diperbarui_pada' => Carbon::now(),
                    'diperbarui_oleh' => 'system'
                ];
            }
        }
        
        DB::table('guru_mata_pelajaran')->insert($guruMataPelajaran);
    }
}