<?php

namespace Database\Seeders;

use App\Models\Staf;
use Illuminate\Database\Seeder;

class StafSeeder extends Seeder
{
    // public function run()
    // {
    //     $jabatan = [
    //         'Kepala Tata Usaha',
    //         'Staf Administrasi',
    //         'Staf Keuangan',
    //         'Staf Perpustakaan',
    //         'Staf Laboratorium'
    //     ];

    //     for ($i = 1; $i <= 5; $i++) {
    //         Staf::create([
    //             'id_user' => $i + 31, // User ID mulai dari 32 (setelah orangtua)
    //             'nama_lengkap' => 'Staf ' . $i,
    //             'nip' => '9876543210' . $i,
    //             'nomor_telepon' => '08765432' . str_pad($i, 4, '0', STR_PAD_LEFT),
    //             'jabatan' => $jabatan[$i - 1],
    //             'dibuat_pada' => now(),
    //             'dibuat_oleh' => 'system'
    //         ]);
    //     }
    // }
}