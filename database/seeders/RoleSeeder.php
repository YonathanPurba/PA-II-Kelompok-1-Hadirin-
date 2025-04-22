<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id_role' => 1,
                'role' => 'staf',
                'deskripsi' => 'Staf Sekolah'
            ],
            [
                'id_role' => 2,
                'role' => 'guru',
                'deskripsi' => 'Guru'
            ],
            [
                'id_role' => 3,
                'role' => 'orangtua',
                'deskripsi' => 'Orangtua Siswa'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}