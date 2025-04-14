<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuruMataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'guru_mata_pelajaran';
    protected $primaryKey = 'id_guru_mata_pelajaran';
    public $timestamps = false;

    protected $fillable = [
        'id_guru',
        'id_mata_pelajaran',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
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
}
