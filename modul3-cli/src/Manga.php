<?php

namespace FlipPlay;

// Child class Manga turunan dari Media
class Manga extends Media
{
    private int $chapter;
    private string $penulis;

    public function __construct(string $judul, float $rating, int $tahun, int $chapter, string $penulis, string $status = "Publishing")
    {
        parent::__construct($judul, $rating, $tahun, $status);
        $this->chapter = $chapter;
        $this->penulis = $penulis;
    }

    public function getChapter(): int
    {
        return $this->chapter;
    }

    public function getPenulis(): string
    {
        return $this->penulis;
    }

    public function getTipe(): string
    {
        return "Manga";
    }

    public function getInfoSingkat(): string
    {
        return "[Manga] {$this->judul} | Rating: {$this->rating} | {$this->chapter} Chapter";
    }

    // Penerapan polymorphism: menampilkan detail khusus manga
    public function tampilkanDetail(): void
    {
        echo "[Detail Manga]\n";
        echo "Judul   : " . $this->judul . "\n";
        echo "Tahun   : " . $this->tahun . "\n";
        echo "Rating  : " . $this->rating . " / 10\n";
        echo "Status  : " . $this->status . "\n";
        echo "Penulis : " . $this->penulis . "\n";
        echo "Chapter : " . $this->chapter . " Chapter\n";
    }
}
