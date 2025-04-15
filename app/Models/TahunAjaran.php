<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';
    public $timestamps = false; // karena menggunakan kolom kustom

    protected $fillable = [
        'nama_tahun_ajaran',
        'tanggal_mulai',
        'tanggal_selesai',
        'aktif',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh',
    ];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_tahun_ajaran');
    }

    // (Opsional) Kamu bisa tambahkan juga relasi ke entitas lain jika dibutuhkan
}
