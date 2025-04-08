<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    public $timestamps = false;

    protected $fillable = [
        'nama_lengkap',
        'nisn',
        'jenis_kelamin',
        'kelas_id',
        'id_user',
        'id_orang_tua',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'id_orang_tua');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_siswa');
    }

    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class, 'id_siswa');
    }
}