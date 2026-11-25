<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    /**
     * Properti fillable untuk mass assignment
     */
    protected $fillable = [
        'judul',
        'tipe',
        'genre',
        'rating',
        'tahun',
        'status',
        'episode',
        'chapter',
        'studio',
        'penulis',
        'network',
        'deskripsi',
    ];

    /**
     * Type casting
     */
    protected function casts(): array
    {
        return [
            'rating' => 'float',
            'tahun' => 'integer',
            'episode' => 'integer',
            'chapter' => 'integer',
        ];
    }

    /**
     * Relasi many-to-many ke User (koleksi pengguna)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'media_user')
            ->withPivot('status', 'catatan')
            ->withTimestamps();
    }

    /**
     * Query Scope untuk pencarian berdasarkan judul
     */
    public function scopeSearch($query, ?string $keyword)
    {
        if (!empty($keyword)) {
            return $query->where('judul', 'like', "%{$keyword}%");
        }
        return $query;
    }

    /**
     * Query Scope untuk filter berdasarkan tipe (Anime, Manga, Drama)
     */
    public function scopeTipe($query, ?string $tipe)
    {
        if (!empty($tipe)) {
            return $query->where('tipe', $tipe);
        }
        return $query;
    }

    /**
     * Accessor untuk informasi singkat (penerapan fitur dari Modul 3 & Modul 4)
     */
    protected function infoSingkat(): Attribute
    {
        return Attribute::make(
            get: function () {
                $detail = match ($this->tipe) {
                    'Anime' => "{$this->episode} Episode | Studio: {$this->studio}",
                    'Manga' => "{$this->chapter} Chapter | Penulis: {$this->penulis}",
                    'Drama' => "{$this->episode} Episode | Network: {$this->network}",
                    default => "Tahun {$this->tahun}",
                };
                return "[{$this->tipe}] {$this->judul} | Rating: {$this->rating} | {$detail}";
            }
        );
    }
}
