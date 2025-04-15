<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    public $timestamps = false; // karena menggunakan kolom waktu kustom

    protected $fillable = [
        'nama',
        'nis',
        'id_orangtua',
        'id_kelas',
        'id_tahun_ajaran',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
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

    // Relasi ke tahun ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran');
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
