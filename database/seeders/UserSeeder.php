<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];
        $id = 1;
        
        // Admin users (10 users)
        for ($i = 1; $i <= 10; $i++) {
            $users[] = [
                'id_user' => $id++,
                'username' => 'staf' . $i,
                'password' => Hash::make('password'),
                'id_role' => 1, // staf
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ];
        }
        
        // Teacher users (30 users)
        for ($i = 1; $i <= 30; $i++) {
            $users[] = [
                'id_user' => $id++,
                'username' => 'guru' . $i,
                'password' => Hash::make('password'),
                'id_role' => 3, // guru
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ];
        }
        
        // Parent users (100 users)
        for ($i = 1; $i <= 100; $i++) {
            $users[] = [
                'id_user' => $id++,
                'username' => 'ortu' . $i,
                'password' => Hash::make('password'),
                'id_role' => 2, // orangtua
                'fcm_token' => null,
                'remember_token' => null,
                'last_login_at' => null,
                'dibuat_pada' => Carbon::now(),
                'dibuat_oleh' => 'system',
                'diperbarui_pada' => Carbon::now(),
                'diperbarui_oleh' => 'system'
            ];
        }
        
        DB::table('users')->insert($users);
    }
}