<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id_mata_pelajaran';
    public $timestamps = false;

    protected $fillable = [
        'nama_mata_pelajaran',
        'deskripsi_mata_pelajaran',
        'id_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function guru()
    {
        return $this->hasMany(Guru::class, 'id_mata_pelajaran');
    }

    public function jadwalPelajaran()
    {
        return $this->hasMany(Jadwal::class, 'mata_pelajaran_id');
    }
}