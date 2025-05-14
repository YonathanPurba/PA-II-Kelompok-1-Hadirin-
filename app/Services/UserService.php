<?php

namespace App\Services;

use App\Models\User;
use App\Models\Guru;
use App\Models\OrangTua;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserService
{
    public static function createGuruWithUser(array $guruData, array $userData, $creatorId = null)
    {
        return DB::transaction(function () use ($guruData, $userData, $creatorId) {
            $now = Carbon::now();

            // Buat User terlebih dahulu tanpa email
            $user = User::create([
                'username' => $userData['username'],
                'password' => Hash::make($userData['password'] ?? Str::random(8)),
                'id_role' => $userData['id_role'], // Pastikan id_role = GURU
                'nomor_telepon' => $userData['nomor_telepon'] ?? null,
                'dibuat_pada' => $now,
                'dibuat_oleh' => $creatorId,
            ]);

            // Tambahkan id_user ke data guru
            $guruData['id_user'] = $user->id_user;
            $guruData['dibuat_pada'] = $now;
            $guruData['dibuat_oleh'] = $creatorId;

            $guru = Guru::create($guruData);

            return [$user, $guru];
        });
    }

    public static function createOrangTuaWithUser(array $ortuData, array $userData, $creatorId = null)
    {
        return DB::transaction(function () use ($ortuData, $userData, $creatorId) {
            $now = Carbon::now();

            // Buat User terlebih dahulu tanpa email
            $user = User::create([
                'nama' => $ortuData['nama_lengkap'],
                'password' => Hash::make($userData['password'] ?? Str::random(8)),
                'id_role' => $userData['id_role'], // Pastikan id_role = ORANGTUA
                'nomor_telepon' => $userData['nomor_telepon'] ?? null,
                'dibuat_pada' => $now,
                'dibuat_oleh' => $creatorId,
            ]);

            $ortuData['id_user'] = $user->id;
            $ortuData['dibuat_pada'] = $now;
            $ortuData['dibuat_oleh'] = $creatorId;

            $orangTua = OrangTua::create($ortuData);

            return [$user, $orangTua];
        });
    }

    public static function resetPassword(User $user, $newPassword = null)
    {
        $newPassword = $newPassword ?? Str::random(8);
        $user->password = Hash::make($newPassword);
        $user->save();
        return $newPassword;
    }

    public static function updateEmail(User $user, $email)
    {
        $user->email = $email;
        $user->save();
        return $user;
    }
    public static function updateGuruWithUser($guruId, array $guruData, array $userData)
    {
        DB::transaction(function () use ($guruId, $guruData, $userData) {
            $guru = Guru::findOrFail($guruId);

            $guru->update($guruData);

            if ($guru->user) {
                $guru->user->update($userData);
            }
        });
    }
    public static function updateOrangTuaWithUser($orangTuaId, array $ortuData, array $userData)
    {
        DB::transaction(function () use ($orangTuaId, $ortuData, $userData) {
            // Cari data orang tua
            $orangTua = OrangTua::findOrFail($orangTuaId);

            // Update data orang tua
            $orangTua->update($ortuData);

            // Update data user terkait jika ada
            if ($orangTua->user) {
                $orangTua->user->update($userData);
            }
        });
    }
}
