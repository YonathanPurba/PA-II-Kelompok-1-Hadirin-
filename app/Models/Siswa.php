<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'siswa';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_siswa';

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
        'nama',
        'nis',
        'id_orangtua',
        'id_kelas',
        'id_tahun_ajaran',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'status',
        'dibuat_pada',
        'dibuat_oleh',
        'diperbarui_pada',
        'diperbarui_oleh',
    ];

    /**
     * Get the class that the student belongs to.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Get the parent of the student.
     */
    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'id_orangtua', 'id_orangtua');
    }

    /**
     * Get the academic year the student is enrolled in.
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    /**
     * Get the attendances for the student.
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Get the permission letters for the student.
     */
    public function suratIzin()
    {
        return $this->hasMany(SuratIzin::class, 'id_siswa', 'id_siswa');
    }
}
