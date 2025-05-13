<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataPelajaran = [
            ['id_mata_pelajaran' => 1, 'nama' => 'Matematika', 'kode' => 'MTK', 'deskripsi' => 'Pelajaran tentang angka, ruang, kuantitas dan perubahan'],
            ['id_mata_pelajaran' => 2, 'nama' => 'Bahasa Indonesia', 'kode' => 'BIN', 'deskripsi' => 'Pelajaran tentang bahasa dan sastra Indonesia'],
            ['id_mata_pelajaran' => 3, 'nama' => 'Bahasa Inggris', 'kode' => 'BIG', 'deskripsi' => 'Pelajaran tentang bahasa Inggris'],
            ['id_mata_pelajaran' => 4, 'nama' => 'IPA', 'kode' => 'IPA', 'deskripsi' => 'Pelajaran tentang ilmu pengetahuan alam'],
            ['id_mata_pelajaran' => 5, 'nama' => 'IPS', 'kode' => 'IPS', 'deskripsi' => 'Pelajaran tentang ilmu pengetahuan sosial'],
            ['id_mata_pelajaran' => 6, 'nama' => 'Pendidikan Agama', 'kode' => 'PAI', 'deskripsi' => 'Pelajaran tentang agama dan moral'],
            ['id_mata_pelajaran' => 7, 'nama' => 'PPKN', 'kode' => 'PKN', 'deskripsi' => 'Pelajaran tentang pendidikan kewarganegaraan'],
            ['id_mata_pelajaran' => 8, 'nama' => 'Seni Budaya', 'kode' => 'SBD', 'deskripsi' => 'Pelajaran tentang seni dan budaya'],
            ['id_mata_pelajaran' => 9, 'nama' => 'Pendidikan Jasmani', 'kode' => 'PJK', 'deskripsi' => 'Pelajaran tentang olahraga dan kesehatan'],
            ['id_mata_pelajaran' => 10, 'nama' => 'Prakarya', 'kode' => 'PKR', 'deskripsi' => 'Pelajaran tentang keterampilan dan kerajinan'],
            ['id_mata_pelajaran' => 11, 'nama' => 'Informatika', 'kode' => 'INF', 'deskripsi' => 'Pelajaran tentang komputer dan teknologi informasi'],
            ['id_mata_pelajaran' => 12, 'nama' => 'Bahasa Daerah', 'kode' => 'BDA', 'deskripsi' => 'Pelajaran tentang bahasa daerah'],
            ['id_mata_pelajaran' => 13, 'nama' => 'Sejarah', 'kode' => 'SEJ', 'deskripsi' => 'Pelajaran tentang sejarah Indonesia dan dunia'],
            ['id_mata_pelajaran' => 14, 'nama' => 'Fisika', 'kode' => 'FIS', 'deskripsi' => 'Pelajaran tentang fisika'],
            ['id_mata_pelajaran' => 15, 'nama' => 'Kimia', 'kode' => 'KIM', 'deskripsi' => 'Pelajaran tentang kimia'],
            ['id_mata_pelajaran' => 16, 'nama' => 'Biologi', 'kode' => 'BIO', 'deskripsi' => 'Pelajaran tentang biologi'],
            ['id_mata_pelajaran' => 17, 'nama' => 'Ekonomi', 'kode' => 'EKO', 'deskripsi' => 'Pelajaran tentang ekonomi'],
            ['id_mata_pelajaran' => 18, 'nama' => 'Sosiologi', 'kode' => 'SOS', 'deskripsi' => 'Pelajaran tentang sosiologi'],
            ['id_mata_pelajaran' => 19, 'nama' => 'Geografi', 'kode' => 'GEO', 'deskripsi' => 'Pelajaran tentang geografi'],
            ['id_mata_pelajaran' => 20, 'nama' => 'Seni Musik', 'kode' => 'SMK', 'deskripsi' => 'Pelajaran tentang seni musik'],
        ];
        
        foreach ($mataPelajaran as &$mapel) {
            $mapel['dibuat_pada'] = Carbon::now();
            $mapel['dibuat_oleh'] = 'system';
            $mapel['diperbarui_pada'] = Carbon::now();
            $mapel['diperbarui_oleh'] = 'system';
        }
        
        DB::table('mata_pelajaran')->insert($mataPelajaran);
    }
}