<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';
    
    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $fillable = [
        'nama_tahun_ajaran',
        'tanggal_mulai',
        'tanggal_selesai',
        'aktif',
        'dibuat_oleh',
        'diperbarui_oleh'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'aktif' => 'boolean',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    /**
     * Status constants for students
     */
    const STATUS_ACTIVE = 'aktif';
    const STATUS_INACTIVE = 'nonaktif';

    /**
     * Set this academic year as active and deactivate others
     * 
     * @return void
     */
    public function setAsActive()
    {
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Deactivate all academic years
            self::where('aktif', true)
                ->update([
                    'aktif' => false,
                    'diperbarui_pada' => now(),
                    'diperbarui_oleh' => Auth::user()->username ?? 'system'
                ]);
        
            // Activate this academic year
            $this->aktif = true;
            $this->diperbarui_pada = now();
            $this->diperbarui_oleh = Auth::user()->username ?? 'system';
            $this->save();
        
            // Update all classes in this academic year
            foreach ($this->kelas as $kelas) {
                $kelas->updateStudentsStatus();
            }
        
            // Update all students directly associated with this academic year
            // but not through a class (if any)
            Siswa::where('id_tahun_ajaran', $this->id_tahun_ajaran)
                ->whereNull('id_kelas')
                ->update([
                    'status' => self::STATUS_ACTIVE,
                    'diperbarui_pada' => now(),
                    'diperbarui_oleh' => Auth::user()->username ?? 'system'
                ]);
        
            DB::commit();
        
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Override the save method to update classes and students when active status changes
     */
    public function save(array $options = [])
    {
        $wasActive = $this->getOriginal('aktif');
        $result = parent::save($options);
        
        // If active status has changed, update all related classes and students
        if ($this->isDirty('aktif') && $wasActive != $this->aktif) {
            // Update all classes in this academic year
            foreach ($this->kelas as $kelas) {
                $kelas->updateStudentsStatus();
            }
        
            // Update all students directly associated with this academic year
            // but not through a class (if any)
            $newStatus = $this->aktif ? self::STATUS_ACTIVE : self::STATUS_INACTIVE;
            Siswa::where('id_tahun_ajaran', $this->id_tahun_ajaran)
                ->whereNull('id_kelas')
                ->update([
                    'status' => $newStatus,
                    'diperbarui_pada' => now(),
                    'diperbarui_oleh' => Auth::user()->username ?? 'system'
                ]);
        }
        
        return $result;
    }
}
