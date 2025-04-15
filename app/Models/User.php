<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    public $timestamps = false; // Karena field timestamp kustom digunakan

    /**
     * Kolom yang bisa diisi secara massal
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'id_role',
        'nomor_telepon',
        'remember_token',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (misalnya ke JSON)
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast kolom ke tipe data tertentu
     *
     * @var array
     */
    protected $casts = [
        'dibuat_pada' => 'datetime',
        'diperbarui_pada' => 'datetime',
        'password' => 'hashed', // Laravel 10+ mendukung cast hashed
    ];

    // RELASI

    public function guru()
    {
        return $this->hasOne(Guru::class, 'id_user');
    }

    public function orangTua()
    {
        return $this->hasOne(OrangTua::class, 'id_user');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_user');
    }

    public function mataPelajaran()
    {
        return $this->hasMany(MataPelajaran::class, 'id_user');
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(Jadwal::class, 'id_user');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_user');
    }
}
