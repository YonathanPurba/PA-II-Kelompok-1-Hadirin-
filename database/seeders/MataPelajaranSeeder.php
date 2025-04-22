<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    public function run()
    {
        $mataPelajaran = [
            [
                'nama' => 'Matematika',
                'kode' => 'MTK',
                'deskripsi' => 'Pelajaran Matematika',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ],
            [
                'nama' => 'Bahasa Indonesia',
                'kode' => 'BIN',
                'deskripsi' => 'Pelajaran Bahasa Indonesia',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ],
            [
                'nama' => 'Bahasa Inggris',
                'kode' => 'BIG',
                'deskripsi' => 'Pelajaran Bahasa Inggris',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ],
            [
                'nama' => 'Ilmu Pengetahuan Alam',
                'kode' => 'IPA',
                'deskripsi' => 'Pelajaran IPA',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ],
            [
                'nama' => 'Ilmu Pengetahuan Sosial',
                'kode' => 'IPS',
                'deskripsi' => 'Pelajaran IPS',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ],
            [
                'nama' => 'Pendidikan Agama',
                'kode' => 'PAI',
                'deskripsi' => 'Pelajaran Pendidikan Agama',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ],
            [
                'nama' => 'Pendidikan Kewarganegaraan',
                'kode' => 'PKN',
                'deskripsi' => 'Pelajaran PKN',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ],
            [
                'nama' => 'Pendidikan Jasmani',
                'kode' => 'PJK',
                'deskripsi' => 'Pelajaran Pendidikan Jasmani',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ]
        ];

        foreach ($mataPelajaran as $mp) {
            MataPelajaran::create($mp);
        }
    }
}