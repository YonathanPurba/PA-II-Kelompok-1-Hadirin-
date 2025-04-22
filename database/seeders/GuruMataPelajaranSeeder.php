<?php

namespace Database\Seeders;

use App\Models\GuruMataPelajaran;
use Illuminate\Database\Seeder;

class GuruMataPelajaranSeeder extends Seeder
{
    public function run()
    {
        $assignments = [
            // Guru 1 mengajar Matematika
            ['id_guru' => 1, 'id_mata_pelajaran' => 1],
            // Guru 2 mengajar Bahasa Indonesia
            ['id_guru' => 2, 'id_mata_pelajaran' => 2],
            // Guru 3 mengajar Bahasa Inggris
            ['id_guru' => 3, 'id_mata_pelajaran' => 3],
            // Guru 4 mengajar IPA
            ['id_guru' => 4, 'id_mata_pelajaran' => 4],
            // Guru 5 mengajar IPS
            ['id_guru' => 5, 'id_mata_pelajaran' => 5],
            // Guru 6 mengajar Pendidikan Agama
            ['id_guru' => 6, 'id_mata_pelajaran' => 6],
            // Guru 7 mengajar PKN
            ['id_guru' => 7, 'id_mata_pelajaran' => 7],
            // Guru 8 mengajar Pendidikan Jasmani
            ['id_guru' => 8, 'id_mata_pelajaran' => 8],
            // Guru 9 mengajar Matematika juga
            ['id_guru' => 9, 'id_mata_pelajaran' => 1],
            // Guru 10 mengajar IPA juga
            ['id_guru' => 10, 'id_mata_pelajaran' => 4],
        ];

        foreach ($assignments as $assignment) {
            GuruMataPelajaran::create([
                'id_guru' => $assignment['id_guru'],
                'id_mata_pelajaran' => $assignment['id_mata_pelajaran'],
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ]);
        }
    }
}