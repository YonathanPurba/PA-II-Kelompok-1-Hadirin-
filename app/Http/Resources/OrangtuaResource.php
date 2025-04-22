<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrangtuaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_orangtua,
            'user' => new UserResource($this->whenLoaded('user')),
            'nama_lengkap' => $this->nama_lengkap,
            'alamat' => $this->alamat,
            'nomor_telepon' => $this->nomor_telepon,
            'pekerjaan' => $this->pekerjaan,
            'siswa' => SiswaResource::collection($this->whenLoaded('siswa')),
            'surat_izin' => SuratIzinResource::collection($this->whenLoaded('suratIzin')),
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
            'created_by' => $this->dibuat_oleh,
            'updated_by' => $this->diperbarui_oleh,
        ];
    }
}