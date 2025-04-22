<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RekapAbsensiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_rekap,
            'siswa' => new SiswaResource($this->whenLoaded('siswa')),
            'kelas' => new KelasResource($this->whenLoaded('kelas')),
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'jumlah_hadir' => $this->jumlah_hadir,
            'jumlah_sakit' => $this->jumlah_sakit,
            'jumlah_izin' => $this->jumlah_izin,
            'jumlah_alpa' => $this->jumlah_alpa,
            'total_absensi' => $this->jumlah_hadir + $this->jumlah_sakit + $this->jumlah_izin + $this->jumlah_alpa,
            'persentase_kehadiran' => $this->jumlah_hadir > 0 ? 
                round(($this->jumlah_hadir / ($this->jumlah_hadir + $this->jumlah_sakit + $this->jumlah_izin + $this->jumlah_alpa)) * 100, 2) : 0,
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
            'created_by' => $this->dibuat_oleh,
            'updated_by' => $this->diperbarui_oleh,
        ];
    }
}