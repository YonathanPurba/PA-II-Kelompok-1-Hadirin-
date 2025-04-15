<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';
    
    protected $fillable = [
        'nama',
        'nis',
        'id_orangtua',
        'id_kelas',
        'tanggal_lahir',
        'jenis_kelamin',
        'dibuat_oleh',
        'diperbarui_oleh',
    ];
    
    public function orangtua()
    {
        return $this->belongsTo(Orangtua::class, 'id_orangtua', 'id_orangtua');
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
    
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_siswa', 'id_siswa');
    }
    
    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class, 'id_siswa', 'id_siswa');
    }
    
    public function rekapAbsensi()
    {
        return $this->hasMany(RekapAbsensi::class, 'id_siswa', 'id_siswa');
    }
}