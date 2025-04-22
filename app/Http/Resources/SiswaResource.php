<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id_siswa,
            'nama' => $this->nama,
            'nis' => $this->nis,
            'orangtua' => new OrangtuaResource($this->whenLoaded('orangtua')),
            'kelas' => new KelasResource($this->whenLoaded('kelas')),
            'tahun_ajaran' => new TahunAjaranResource($this->whenLoaded('tahunAjaran')),
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'alamat' => $this->alamat,
            'absensi' => AbsensiResource::collection($this->whenLoaded('absensi')),
            'surat_izin' => SuratIzinResource::collection($this->whenLoaded('suratIzin')),
            'rekap_absensi' => RekapAbsensiResource::collection($this->whenLoaded('rekapAbsensi')),
            'created_at' => $this->dibuat_pada,
            'updated_at' => $this->diperbarui_pada,
            'created_by' => $this->dibuat_oleh,
            'updated_by' => $this->diperbarui_oleh,
        ];
    }
}