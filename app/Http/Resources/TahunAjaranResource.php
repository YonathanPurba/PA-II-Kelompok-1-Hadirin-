<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TahunAjaranResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_tahun_ajaran,
            'nama_tahun_ajaran' => $this->nama_tahun_ajaran,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'aktif' => $this->aktif,
            'kelas' => KelasResource::collection($this->whenLoaded('kelas')),
            'siswa' => SiswaResource::collection($this->whenLoaded('siswa')),
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
            'created_by' => $this->dibuat_oleh,
            'updated_by' => $this->diperbarui_oleh,
        ];
    }
}