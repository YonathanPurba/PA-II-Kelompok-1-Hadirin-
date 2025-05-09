<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    
    public function activeJadwal()
    {
        return $this->jadwal()->where('status', 'aktif');
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
        // Get the class's academic year
        $tahunAjaran = $this->tahunAjaran;
        
        // Determine the status based on academic year
        $status = ($tahunAjaran && $tahunAjaran->aktif) ? Siswa::STATUS_ACTIVE : Siswa::STATUS_INACTIVE;
        
        // Update all students in this class
        Siswa::where('id_kelas', $this->id_kelas)
            ->update([
                'status' => $status,
                'id_tahun_ajaran' => $tahunAjaran ? $tahunAjaran->id_tahun_ajaran : null,
                'diperbarui_pada' => now(),
                'diperbarui_oleh' => Auth::user()->username ?? 'system'
            ]);
        
        // If we have the relationship loaded, update each model instance
        if ($this->relationLoaded('siswa')) {
            foreach ($this->siswa as $siswa) {
                $siswa->status = $status;
                $siswa->id_tahun_ajaran = $tahunAjaran ? $tahunAjaran->id_tahun_ajaran : null;
                $siswa->diperbarui_pada = now();
                $siswa->diperbarui_oleh = Auth::user()->username ?? 'system';
                $siswa->save();
            }
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
    
    /**
     * Check if class has schedule conflicts
     * 
     * @param string $day
     * @param string $startTime
     * @param string $endTime
     * @param int|null $excludeScheduleId
     * @return bool
     */
    public function hasScheduleConflict($day, $startTime, $endTime, $excludeScheduleId = null)
    {
        $query = $this->jadwal()
            ->where('hari', $day)
            ->where('status', 'aktif')
            ->where(function($q) use ($startTime, $endTime) {
                // Check for time overlaps
                $q->where(function($query) use ($startTime, $endTime) {
                    $query->where('waktu_mulai', '>=', $startTime)
                          ->where('waktu_mulai', '<', $endTime);
                })->orWhere(function($query) use ($startTime, $endTime) {
                    $query->where('waktu_selesai', '>', $startTime)
                          ->where('waktu_selesai', '<=', $endTime);
                })->orWhere(function($query) use ($startTime, $endTime) {
                    $query->where('waktu_mulai', '<=', $startTime)
                          ->where('waktu_selesai', '>=', $endTime);
                });
            });
            
        if ($excludeScheduleId) {
            $query->where('id_jadwal', '!=', $excludeScheduleId);
        }
        
        return $query->exists();
    }

    /**
     * Override the save method to update students when academic year changes
     */
    public function save(array $options = [])
    {
        $oldTahunAjaranId = $this->getOriginal('id_tahun_ajaran');
        $result = parent::save($options);
        
        // If academic year has changed, update all students in this class
        if ($this->isDirty('id_tahun_ajaran') && $oldTahunAjaranId != $this->id_tahun_ajaran) {
            $this->updateStudentsStatus();
        }
        
        return $result;
    }
}