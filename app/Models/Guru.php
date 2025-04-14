<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    public $timestamps = false;

    protected $fillable = [
        'nip',
        'nama_lengkap',
        'foto_profil', // Pastikan kolom ini sesuai dengan yang ada di migrasi Anda
        'id_user', // Pastikan id_user digunakan di tabel guru
        // 'bidang_studi', // Kolom bidang_studi di migrasi
        'dibuat_pada',
        // 'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function mataPelajaran()
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mata_pelajaran', 'id_guru', 'id_mata_pelajaran')
            ->withPivot('dibuat_pada', 'dibuat_oleh', 'diperbarui_pada', 'diperbarui_oleh');
    }
    // App\Models\Guru.php

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_guru')->with(['kelas', 'mataPelajaran']);
    }

    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class, 'id_guru');
    }

    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'id_guru', 'id_guru');
    }
}
