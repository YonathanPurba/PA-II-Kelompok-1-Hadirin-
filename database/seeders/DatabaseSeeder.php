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
            RoleSeeder::class,  // Pastikan RoleSeeder dijalankan lebih dulu
            UserSeeder::class,  // Kemudian UserSeeder agar user memiliki role yang valid
            MataPelajaranSeeder::class,
            GuruSeeder::class,
            GuruMataPelajaranSeeder::class,
        ]);
    }
}
