<?php

namespace FlipPlay;

// Class induk Media (abstract class)
abstract class Media
{
    // Property protected agar bisa diakses oleh class turunan
    protected string $judul;
    protected float $rating;
    protected int $tahun;
    protected string $status;

    public function __construct(string $judul, float $rating, int $tahun, string $status = "Selesai")
    {
        $this->judul = $judul;
        $this->rating = $rating;
        $this->tahun = $tahun;
        $this->status = $status;
    }

    public function getJudul(): string
    {
        return $this->judul;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getTahun(): int
    {
        return $this->tahun;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    // Method abstract untuk polymorphism pada class turunan
    abstract public function getTipe(): string;
    abstract public function tampilkanDetail(): void;
    abstract public function getInfoSingkat(): string;
}
