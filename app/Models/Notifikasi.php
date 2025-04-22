<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_user',
        'judul',
        'pesan',
        'tipe',
        'dibaca',
        'waktu_dibaca',
        'dibuat_oleh',
        'diperbarui_oleh'
    ];

    protected $casts = [
        'dibaca' => 'boolean',
        'waktu_dibaca' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}