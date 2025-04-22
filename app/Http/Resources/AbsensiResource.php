<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsensiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_absensi,
            'siswa' => new SiswaResource($this->whenLoaded('siswa')),
            'jadwal' => new JadwalResource($this->whenLoaded('jadwal')),
            'tanggal' => $this->tanggal,
            'status' => $this->status,
            'catatan' => $this->catatan,
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
            'created_by' => $this->dibuat_oleh,
            'updated_by' => $this->diperbarui_oleh,
        ];
    }
}