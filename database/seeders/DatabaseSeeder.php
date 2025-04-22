<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            TahunAjaranSeeder::class,
            MataPelajaranSeeder::class,
            GuruSeeder::class,
            KelasSeeder::class,
            OrangtuaSeeder::class,
            SiswaSeeder::class,
            GuruMataPelajaranSeeder::class,
            JadwalSeeder::class,
            AbsensiSeeder::class,
            SuratIzinSeeder::class,
            RekapAbsensiSeeder::class,
            NotifikasiSeeder::class,
            // StafSeeder::class,
        ]);
    }
}