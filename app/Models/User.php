<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{   
    protected $primaryKey = 'id_user';

    public $timestamps = false; // Karena menggunakan field timestamp kustom

    protected $fillable = [
        'nama',
        'email',
        'password',
        'id_role',
        'nomor_telepon',
        'remember_token',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        return $this->hasMany(JadwalPelajaran::class, 'id_user');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_user');
    }
}
