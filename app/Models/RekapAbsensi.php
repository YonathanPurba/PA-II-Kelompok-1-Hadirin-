<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapAbsensi extends Model
{
    use HasFactory;

    protected $table = 'rekap_absensi';
    protected $primaryKey = 'id_rekap';
    public $timestamps = false;

    protected $fillable = [
        'id_siswa',
        'id_kelas',
        'bulan',
        'tahun',
        'jumlah_hadir',
        'jumlah_sakit',
        'jumlah_izin',
        'jumlah_alpa',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
}
