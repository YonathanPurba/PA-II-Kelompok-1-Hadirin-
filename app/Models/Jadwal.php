<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    
    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';
    
    protected $fillable = [
        'id_kelas',
        'id_mata_pelajaran',
        'id_guru',
        'id_guru_mata_pelajaran',
        'hari',
        'semester',
        'id_tahun_ajaran',
        'waktu_mulai',
        'waktu_selesai',
        'dibuat_oleh',
        'diperbarui_oleh',
    ];
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
    
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }
    
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
    
    public function guruMataPelajaran()
    {
        return $this->belongsTo(GuruMataPelajaran::class, 'id_guru_mata_pelajaran', 'id_guru_mata_pelajaran');
    }
    
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }
    
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_jadwal', 'id_jadwal');
    }
}