<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'id_jadwal';
    
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'id_kelas',
        'id_mata_pelajaran',
        'id_guru',
        'id_tahun_ajaran', // Add this field to fillable
        'hari',
        'waktu_mulai',
        'waktu_selesai',
        'status', // Add status field
        'dibuat_oleh',
        'diperbarui_oleh'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mata_pelajaran', 'id_mata_pelajaran');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
    
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_jadwal', 'id_jadwal');
    }
    
    /**
     * Check for scheduling conflicts with other classes
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getConflicts()
    {
        return self::where('hari', $this->hari)
            ->where('id_jadwal', '!=', $this->id_jadwal)
            ->where(function($query) {
                // Check for time overlaps
                $query->where(function($q) {
                    $q->where('waktu_mulai', '>=', $this->waktu_mulai)
                      ->where('waktu_mulai', '<', $this->waktu_selesai);
                })->orWhere(function($q) {
                    $q->where('waktu_selesai', '>', $this->waktu_mulai)
                      ->where('waktu_selesai', '<=', $this->waktu_selesai);
                })->orWhere(function($q) {
                    $q->where('waktu_mulai', '<=', $this->waktu_mulai)
                      ->where('waktu_selesai', '>=', $this->waktu_selesai);
                });
            })
            ->where(function($query) {
                // Either same class or same teacher
                $query->where('id_kelas', $this->id_kelas)
                      ->orWhere('id_guru', $this->id_guru);
            })
            ->where('status', 'aktif')
            ->get();
    }
    
    /**
     * Check if this schedule has conflicts
     * 
     * @return bool
     */
    public function hasConflicts()
    {
        return $this->getConflicts()->count() > 0;
    }
}