<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotifikasiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_notifikasi,
            'user' => new UserResource($this->whenLoaded('user')),
            'judul' => $this->judul,
            'pesan' => $this->pesan,
            'tipe' => $this->tipe,
            'dibaca' => (bool) $this->dibaca,
            'waktu_dibaca' => $this->waktu_dibaca,
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
            'created_by' => $this->dibuat_oleh,
            'updated_by' => $this->diperbarui_oleh,
        ];
    }
}