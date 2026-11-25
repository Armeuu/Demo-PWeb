<?php

namespace FlipPlay;

// Child class Anime turunan dari Media
class Anime extends Media
{
    private int $episode;
    private string $studio;

    public function __construct(string $judul, float $rating, int $tahun, int $episode, string $studio, string $status = "Selesai")
    {
        parent::__construct($judul, $rating, $tahun, $status);
        $this->episode = $episode;
        $this->studio = $studio;
    }

    public function getEpisode(): int
    {
        return $this->episode;
    }

    public function getStudio(): string
    {
        return $this->studio;
    }

    public function getTipe(): string
    {
        return "Anime";
    }

    public function getInfoSingkat(): string
    {
        return "[Anime] {$this->judul} | Rating: {$this->rating} | {$this->episode} Episode";
    }

    // Penerapan polymorphism: menampilkan detail khusus anime
    public function tampilkanDetail(): void
    {
        echo "[Detail Anime]\n";
        echo "Judul   : " . $this->judul . "\n";
        echo "Tahun   : " . $this->tahun . "\n";
        echo "Rating  : " . $this->rating . " / 10\n";
        echo "Status  : " . $this->status . "\n";
        echo "Studio  : " . $this->studio . "\n";
        echo "Episode : " . $this->episode . " Episode\n";
    }
}
