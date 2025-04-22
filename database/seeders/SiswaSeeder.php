<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');
        
        $kelas_ids = range(1, 12); // 12 kelas (6 tingkat x 2 kelas)
        $orangtua_ids = range(1, 20); // 20 orangtua
        
        $jenis_kelamin = ['laki-laki', 'perempuan'];
        $kota_lahir = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang', 'Medan', 'Makassar', 'Palembang'];
        
        // Buat 60 siswa (rata-rata 5 siswa per kelas)
        for ($i = 1; $i <= 60; $i++) {
            $jk = $jenis_kelamin[array_rand($jenis_kelamin)];
            $kelas_id = $kelas_ids[array_rand($kelas_ids)];
            $orangtua_id = $orangtua_ids[array_rand($orangtua_ids)];
            
            Siswa::create([
                'nama' => $faker->name($jk == 'laki-laki' ? 'male' : 'female'),
                'nis' => '2023' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'id_orangtua' => $orangtua_id,
                'id_kelas' => $kelas_id,
                'id_tahun_ajaran' => 1,
                'tempat_lahir' => $kota_lahir[array_rand($kota_lahir)],
                'tanggal_lahir' => $faker->dateTimeBetween('-15 years', '-6 years')->format('Y-m-d'),
                'jenis_kelamin' => $jk,
                'alamat' => $faker->address,
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ]);
        }
    }
}