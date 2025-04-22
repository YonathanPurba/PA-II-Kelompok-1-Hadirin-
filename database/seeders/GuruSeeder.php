<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    public function run()
    {
<<<<<<< Updated upstream
        $bidangStudi = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA',
            'IPS',
            'Pendidikan Agama',
            'PKN',
            'Pendidikan Jasmani',
            'Seni Budaya',
            'Prakarya'
        ];

        for ($i = 1; $i <= 10; $i++) {
            Guru::create([
                'id_user' => $i + 1, // User ID mulai dari 2 (setelah admin)
                'nama_lengkap' => 'Guru ' . $i,
                'nip' => '1234567890' . $i,
                'nomor_telepon' => '08123456' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'bidang_studi' => $bidangStudi[$i - 1],
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'system'
            ]);
        }
=======
        // Insert ke tabel guru
        DB::table('guru')->insert([
            [
                'id_user' => 3,
                'nama_lengkap' => 'Budi Santoso', // ✅ Tambahkan nama_lengkap
                'nip' => '1987654321',
                'nomor_telepon' => '1987654321',
                'bidang_studi' => 'Matematika',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_user' => 3,
                'nama_lengkap' => 'Siti Aminah', // ✅ Tambahkan nama_lengkap
                'nip' => '1987654322',
                'nomor_telepon' => '1987654321',
                'bidang_studi' => 'Bahasa Indonesia',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            // Guru tambahan
            [
                'id_user' => 3,
                'nama_lengkap' => 'Rudi Hartono', // Guru tambahan 1
                'nip' => '1987654323',
                'nomor_telepon' => '1987654323',
                'bidang_studi' => 'Fisika',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
            [
                'id_user' => 3,
                'nama_lengkap' => 'Anisa Fauziyah', // Guru tambahan 2
                'nip' => '1987654324',
                'nomor_telepon' => '1987654324',
                'bidang_studi' => 'Kimia',
                'dibuat_pada' => now(),
                'dibuat_oleh' => 'Seeder',
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => 'Seeder',
            ],
        ]);
>>>>>>> Stashed changes
    }
}