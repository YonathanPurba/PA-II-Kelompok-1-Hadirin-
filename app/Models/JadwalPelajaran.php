<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pelajaran';
    protected $primaryKey = 'id_jadwal';
    
    protected $fillable = [
        'id_jam',
        'kelas_id',
        'mata_pelajaran_id',
        'guru_id',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_jadwal');
    }
}