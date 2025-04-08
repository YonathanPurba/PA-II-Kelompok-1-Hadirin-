<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';
    
    protected $fillable = [
        'tanggal_absensi',
        'id_user',
        'id_jadwal',
        'id_siswa',
        'id_surat_izin',
        'status_absensi',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'id_jadwal');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function suratIzin()
    {
        return $this->belongsTo(SuratIzin::class, 'id_surat_izin');
    }
}