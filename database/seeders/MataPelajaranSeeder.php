<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mata_pelajaran')->insert([
            [
                'nama' => 'Matematika',
                'kode' => 'MAT101',
                'deskripsi' => 'Pelajaran Matematika dasar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bahasa Indonesia',
                'kode' => 'BIN102',
                'deskripsi' => 'Pelajaran Bahasa Indonesia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Fisika',
                'kode' => 'FIS103',
                'deskripsi' => 'Pelajaran Fisika dasar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
