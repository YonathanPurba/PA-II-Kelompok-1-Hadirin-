<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MataPelajaranResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_mata_pelajaran,
            'nama' => $this->nama,
            'kode' => $this->kode,
            'deskripsi' => $this->deskripsi,
            'guru' => GuruResource::collection($this->whenLoaded('guru')),
            'jadwal' => JadwalResource::collection($this->whenLoaded('jadwal')),
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
            'created_by' => $this->dibuat_oleh,
            'updated_by' => $this->diperbarui_oleh,
        ];
    }
}