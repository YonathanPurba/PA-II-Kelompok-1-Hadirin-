<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StafResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_staf,
            'user' => new UserResource($this->whenLoaded('user')),
            'nama_lengkap' => $this->nama_lengkap,
            'nip' => $this->nip,
            'nomor_telepon' => $this->nomor_telepon,
            'jabatan' => $this->jabatan,
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
            'created_by' => $this->dibuat_oleh,
            'updated_by' => $this->diperbarui_oleh,
        ];
    }
}