<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratIzin extends Model
{
    use HasFactory;

    protected $table = 'surat_izin';
    protected $primaryKey = 'id_surat_izin';
    public $timestamps = false;

    protected $fillable = [
        'id_siswa',
        'tanggal',
        'isi_surat',
        'status',
        'id_guru',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_surat_izin');
    }
}