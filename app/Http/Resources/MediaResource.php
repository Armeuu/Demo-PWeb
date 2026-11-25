<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'tipe' => $this->tipe,
            'genre' => $this->genre,
            'rating' => $this->rating,
            'tahun' => $this->tahun,
            'status' => $this->status,
            'episode' => $this->episode,
            'chapter' => $this->chapter,
            'studio' => $this->studio,
            'penulis' => $this->penulis,
            'network' => $this->network,
            'deskripsi' => $this->deskripsi,
            'info_singkat' => $this->info_singkat,
            'koleksi_info' => $this->whenPivotLoaded('media_user', function () {
                return [
                    'status_koleksi' => $this->pivot->status,
                    'catatan' => $this->pivot->catatan,
                    'ditambahkan_pada' => $this->pivot->created_at?->format('Y-m-d H:i:s'),
                ];
            }),
            'pengguna_koleksi' => UserResource::collection($this->whenLoaded('users')),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
