<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orangtua extends Model
{
    use HasFactory;

    protected $table = 'orangtua';
    protected $primaryKey = 'id_orangtua';
    
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'alamat',
        'nomor_telepon',
        'pekerjaan',
        'dibuat_oleh',
        'diperbarui_oleh'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_orangtua', 'id_orangtua');
    }

    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class, 'id_orangtua', 'id_orangtua');
    }
}