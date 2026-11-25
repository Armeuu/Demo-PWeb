<?php

namespace FlipPlay;

// Class untuk mengelola daftar media
class Katalog
{
    private string $nama;
    private array $daftarMedia = [];

    public function __construct(string $nama = "Katalog FlipPlay")
    {
        $this->nama = $nama;
    }

    public function tambahMedia(Media $media): void
    {
        $this->daftarMedia[] = $media;
    }

    public function getSemuaMedia(): array
    {
        return $this->daftarMedia;
    }

    // Menampilkan daftar seluruh media
    public function tampilkanKatalog(): void
    {
        if (empty($this->daftarMedia)) {
            echo "Katalog masih kosong.\n";
            return;
        }

        foreach ($this->daftarMedia as $index => $media) {
            echo ($index + 1) . ". " . $media->getInfoSingkat() . "\n";
        }
    }

    // Mencari media berdasarkan kata kunci judul
    public function cariMedia(string $keyword): array
    {
        $hasil = [];
        foreach ($this->daftarMedia as $media) {
            if (stripos($media->getJudul(), $keyword) !== false) {
                $hasil[] = $media;
            }
        }
        return $hasil;
    }
}
