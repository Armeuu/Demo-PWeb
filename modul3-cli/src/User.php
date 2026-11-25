<?php

namespace FlipPlay;

// Class User untuk mengelola watchlist / koleksi pribadi
class User
{
    private string $nama;
    private string $email;
    private array $koleksi = [];

    public function __construct(string $nama, string $email)
    {
        $this->nama = $nama;
        $this->email = $email;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    // Menambahkan media ke koleksi
    public function tambahKoleksi(Media $media, string $status): void
    {
        $this->koleksi[] = [
            'media' => $media,
            'status' => $status
        ];
    }

    // Menghapus media dari koleksi berdasarkan judul
    public function hapusKoleksi(string $judul): bool
    {
        foreach ($this->koleksi as $index => $item) {
            if (strcasecmp($item['media']->getJudul(), $judul) === 0) {
                unset($this->koleksi[$index]);
                $this->koleksi = array_values($this->koleksi);
                return true;
            }
        }
        return false;
    }

    // Menampilkan isi koleksi user
    public function tampilkanKoleksi(): void
    {
        if (empty($this->koleksi)) {
            echo "Koleksi kosong.\n";
            return;
        }

        foreach ($this->koleksi as $index => $item) {
            echo ($index + 1) . ". " . $item['media']->getJudul() . " (Status: " . $item['status'] . ")\n";
        }
    }
}
