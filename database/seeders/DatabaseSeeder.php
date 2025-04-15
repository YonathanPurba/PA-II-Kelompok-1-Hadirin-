<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menjalankan seeder untuk role terlebih dahulu
        $this->call([
            UserSeeder::class,  
            MataPelajaranSeeder::class,
            GuruSeeder::class,
            GuruMataPelajaranSeeder::class,
            TahunAjaranSeeder::class,
            KelasSeeder::class,
            JadwalSeeder::class,
            OrangtuaSeeder::class,
            // SiswaSeeder::class,
            // AbsensiSeeder::class,
            // RekapAbsensiSeeder::class,
        ]);
    }
}
