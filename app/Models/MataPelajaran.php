<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id_mata_pelajaran';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
    ];

    public function guru()
    {
        return $this->belongsToMany(Guru::class, 'guru_mata_pelajaran', 'id_mata_pelajaran', 'id_guru')
            ->using(GuruMataPelajaran::class)
            ->withPivot('dibuat_pada', 'dibuat_oleh', 'diperbarui_pada', 'diperbarui_oleh');
    }

    // Relasi ke jadwal pelajaran (jika ada foreign key)
    public function jadwalPelajaran()
    {
        return $this->hasMany(Jadwal::class, 'mata_pelajaran_id');
    }
}
