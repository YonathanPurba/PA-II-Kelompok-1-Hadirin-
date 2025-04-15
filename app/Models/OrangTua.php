<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orangtua extends Model
{
    use HasFactory;

    protected $table = 'orangtua';
    protected $primaryKey = 'id_orangtua';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'alamat',
        'pekerjaan',
        'dibuat_oleh',
        'diperbarui_oleh',
    ];

    /**
     * Relasi ke user (akun yang digunakan orang tua).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke banyak siswa (anak-anak dari orang tua ini).
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_orangtua', 'id_orangtua');
    }

    /**
     * Relasi ke banyak surat izin yang diajukan oleh orang tua ini.
     */
    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class, 'id_orangtua', 'id_orangtua');
    }
}
