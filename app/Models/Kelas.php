<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'id_guru',
        'id_tahun_ajaran',
        'dibuat_oleh',
        'diperbarui_oleh'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_kelas', 'id_kelas');
    }

    public function rekapAbsensi()
    {
        return $this->hasMany(RekapAbsensi::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Update all students' status in this class
     * 
     * @return void
     */
    public function updateStudentsStatus()
    {
        foreach ($this->siswa as $siswa) {
            $siswa->updateStatusBasedOnClass();
        }
    }
    
    /**
     * Check if class is active based on academic year
     * 
     * @return bool
     */
    public function isActive()
    {
        return $this->tahunAjaran && $this->tahunAjaran->aktif;
    }
    
    /**
     * Get status badge HTML
     * 
     * @return string
     */
    public function getStatusBadgeHtml()
    {
        if ($this->isActive()) {
            return '<span class="badge bg-success">Aktif</span>';
        } else {
            return '<span class="badge bg-secondary">Non-Aktif</span>';
        }
    }
}
