<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id_role' => 1,
                'role' => 'staf',
                'deskripsi' => 'Administrator sistem'
            ],
            [
                'id_role' => 2,
                'role' => 'orangtua',
                'deskripsi' => 'Orangtua atau wali siswa'
            ],
            [
                'id_role' => 3,
                'role' => 'guru',
                'deskripsi' => 'Guru atau pengajar'
            ],
        ];

        DB::table('role')->insert($roles);
    }
}