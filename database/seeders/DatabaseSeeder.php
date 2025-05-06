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
            MataPelajaranSeeder::class,
            GuruSeeder::class,
            GuruMataPelajaranSeeder::class,
            KelasSeeder::class,
            OrangtuaSeeder::class,
            SiswaSeeder::class,
            JadwalSeeder::class,
            AbsensiSeeder::class,
            RekapAbsensiSeeder::class,
            SuratIzinSeeder::class,
            NotifikasiSeeder::class,
        ]);
    }
}