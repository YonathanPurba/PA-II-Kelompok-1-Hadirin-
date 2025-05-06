<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    const STATUS_ACTIVE = 'aktif';
    const STATUS_INACTIVE = 'nonaktif';
    const STATUS_PENDING = 'pending';

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
    public $timestamps = true;
    
    /**
     * The column name for the created at timestamp.
     *
     * @var string
     */
    const CREATED_AT = 'dibuat_pada';
    
    /**
     * The column name for the updated at timestamp.
     *
     * @var string
     */
    const UPDATED_AT = 'diperbarui_pada';

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

    /**
     * Update status based on children's status
     * 
     * @return void
     */
    public function updateStatusBasedOnChildren()
    {
        // Get all children of this parent
        $children = $this->siswa;
        
        // If no children, status should be pending
        if ($children->isEmpty()) {
            $this->status = self::STATUS_PENDING;
            $this->save();
            return;
        }
        
        // Check if all children are inactive
        $allInactive = $children->every(function ($child) {
            return $child->status === Siswa::STATUS_INACTIVE;
        });
        
        if ($allInactive) {
            $this->status = self::STATUS_INACTIVE;
        } else {
            // If at least one child is active, parent should be active
            $this->status = self::STATUS_ACTIVE;
        }
        
        $this->save();
    }
    
    /**
     * Get status badge HTML
     * 
     * @return string
     */
    public function getStatusBadgeHtml()
    {
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                return '<span class="badge bg-success">Aktif</span>';
            case self::STATUS_INACTIVE:
                return '<span class="badge bg-secondary">Non-Aktif</span>';
            case self::STATUS_PENDING:
                return '<span class="badge bg-warning text-dark">Pending</span>';
            default:
                return '<span class="badge bg-light text-dark">Unknown</span>';
        }
    }
}
