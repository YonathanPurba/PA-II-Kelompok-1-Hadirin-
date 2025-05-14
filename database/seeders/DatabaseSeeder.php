<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            TahunAjaranSeeder::class,
            UserSeeder::class,
            StafSeeder::class,
            GuruSeeder::class,
            OrangtuaSeeder::class,
            MataPelajaranSeeder::class,
            GuruMataPelajaranSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
            JadwalSeeder::class,
            AbsensiSeeder::class,
            SuratIzinSeeder::class,
            NotifikasiSeeder::class,
            RekapAbsensiSeeder::class,
        ]);
    }
}