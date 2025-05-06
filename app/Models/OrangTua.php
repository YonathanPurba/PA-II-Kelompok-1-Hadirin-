<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orangtua';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_orangtua';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'alamat',
        'nomor_telepon',
        'pekerjaan',
        'status',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh',
    ];

    /**
     * Get the user associated with the parent.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the children (students) for the parent.
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_orangtua', 'id_orangtua');
    }

    /**
     * Get permission letters associated with this parent.
     */
    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class, 'id_orangtua', 'id_orangtua');
    }
}
