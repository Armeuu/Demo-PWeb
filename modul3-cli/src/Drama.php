<?php

namespace FlipPlay;

// Child class Drama turunan dari Media
class Drama extends Media
{
    private int $episode;
    private string $network;

    public function __construct(string $judul, float $rating, int $tahun, int $episode, string $network, string $status = "Selesai")
    {
        parent::__construct($judul, $rating, $tahun, $status);
        $this->episode = $episode;
        $this->network = $network;
    }

    public function getEpisode(): int
    {
        return $this->episode;
    }

    public function getNetwork(): string
    {
        return $this->network;
    }

    public function getTipe(): string
    {
        return "Drama";
    }

    public function getInfoSingkat(): string
    {
        return "[Drama] {$this->judul} | Rating: {$this->rating} | {$this->episode} Episode";
    }

    // Penerapan polymorphism: menampilkan detail khusus drama
    public function tampilkanDetail(): void
    {
        echo "[Detail Drama]\n";
        echo "Judul   : " . $this->judul . "\n";
        echo "Tahun   : " . $this->tahun . "\n";
        echo "Rating  : " . $this->rating . " / 10\n";
        echo "Status  : " . $this->status . "\n";
        echo "Network : " . $this->network . "\n";
        echo "Episode : " . $this->episode . " Episode\n";
    }
}
