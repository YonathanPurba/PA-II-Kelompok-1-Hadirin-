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
        'id_user',
        'nama_lengkap',
        'nip',
        'nomor_telepon',
        'bidang_studi',
        'status',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function mataPelajaran()
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mata_pelajaran', 'id_guru', 'id_mata_pelajaran')
            ->withPivot(['dibuat_pada', 'dibuat_oleh', 'diperbarui_pada', 'diperbarui_oleh']);
        // Remove withTimestamps() as we're using custom timestamp columns
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_guru', 'id_guru');
    }
    
    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'id_guru', 'id_guru');
    }
}