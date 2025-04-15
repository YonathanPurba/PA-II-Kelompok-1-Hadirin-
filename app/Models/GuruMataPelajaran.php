<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruMataPelajaran extends Model
{
    use HasFactory;
    
    protected $table = 'guru_mata_pelajaran';
    protected $primaryKey = 'id_guru_mata_pelajaran';
    
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';
    
    protected $fillable = [
        'id_guru',
        'id_mata_pelajaran',
        'dibuat_oleh',
        'diperbarui_oleh',
    ];
    
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
    
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }
    
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_guru_mata_pelajaran', 'id_guru_mata_pelajaran');
    }
}