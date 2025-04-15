<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;
    
    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id_mata_pelajaran';
    
    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
    ];
    
    public function guru()
    {
        return $this->belongsToMany(
            Guru::class,
            'guru_mata_pelajaran',
            'id_mata_pelajaran',
            'id_guru'
        )->withPivot('id_guru_mata_pelajaran');
    }
    
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }
}