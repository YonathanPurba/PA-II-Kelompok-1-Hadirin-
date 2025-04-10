<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    public $timestamps = false; // karena menggunakan kolom waktu kustom, bukan timestamps default

    protected $fillable = [
        'nama',
        'nis',
        'id_orangtua',
        'id_kelas',
        'tanggal_lahir',
        'jenis_kelamin',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh',
    ];

    // Relasi ke orangtua
    public function orangtua()
    {
        return $this->belongsTo(Orangtua::class, 'id_orangtua');
    }

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    // Relasi ke absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_siswa');
    }

    // Relasi ke rekap absensi
    public function rekapAbsensi()
    {
        return $this->hasMany(RekapAbsensi::class, 'id_siswa');
    }
}
