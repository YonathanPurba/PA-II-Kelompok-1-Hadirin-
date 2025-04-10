<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    public $timestamps = false;

    protected $fillable = [
        'nip',
        'nama',
        'alamat',
        'jenis_kelamin',
        'foto_profil',
        'id_user',
        'id_mata_pelajaran',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran');
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(Jadwal::class, 'guru_id');
    }

    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class, 'id_guru');
    }
    
    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'id_guru_wali', 'id_guru');
    }
}
