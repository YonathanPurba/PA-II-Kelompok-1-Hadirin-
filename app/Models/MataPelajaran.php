<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id_mata_pelajaran';
    
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'dibuat_oleh',
        'diperbarui_oleh'
    ];

    public function guruMataPelajaran()
    {
        return $this->hasMany(GuruMataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    public function guru()
    {
        return $this->belongsToMany(Guru::class, 'guru_mata_pelajaran', 'id_mata_pelajaran', 'id_guru')
            ->withPivot(['dibuat_pada', 'dibuat_oleh', 'diperbarui_pada', 'diperbarui_oleh']);
        // Remove withTimestamps() as we're using custom timestamp columns
    }
}