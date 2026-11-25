<?php

// Autoload class dari folder src
spl_autoload_register(function ($class) {
    $class = str_replace('FlipPlay\\', '', $class);
    $file = __DIR__ . '/src/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use FlipPlay\Anime;
use FlipPlay\Manga;
use FlipPlay\Drama;
use FlipPlay\Katalog;
use FlipPlay\User;

echo "=========================================\n";
echo "         DEMO MODUL 3 - FLIPPLAY         \n";
echo "=========================================\n\n";

// 1. Inisialisasi data media ke dalam katalog
echo "1. Menambahkan Media ke Katalog...\n";
$katalog = new Katalog();

$anime1 = new Anime("Attack on Titan", 8.9, 2013, 89, "WIT Studio & MAPPA");
$anime2 = new Anime("Frieren: Beyond Journey's End", 9.1, 2023, 28, "Madhouse");
$manga1 = new Manga("One Piece", 9.2, 1997, 1115, "Eiichiro Oda");
$manga2 = new Manga("Berserk", 9.4, 1989, 376, "Kentaro Miura");
$drama1 = new Drama("Crash Landing on You", 8.9, 2019, 16, "tvN");

$katalog->tambahMedia($anime1);
$katalog->tambahMedia($anime2);
$katalog->tambahMedia($manga1);
$katalog->tambahMedia($manga2);
$katalog->tambahMedia($drama1);

echo "Berhasil menambahkan 5 data media ke katalog.\n\n";

// 2. Menampilkan seluruh media di katalog
echo "-----------------------------------------\n";
echo "2. Daftar Seluruh Media di Katalog:\n";
echo "-----------------------------------------\n";
$katalog->tampilkanKatalog();
echo "\n";

// 3. Pencarian media berdasarkan judul
echo "-----------------------------------------\n";
echo "3. Pencarian Media:\n";
echo "-----------------------------------------\n";

$keyword1 = "Piece";
echo "Mencari dengan kata kunci: '{$keyword1}'\n";
$hasil1 = $katalog->cariMedia($keyword1);
if (!empty($hasil1)) {
    foreach ($hasil1 as $item) {
        echo "Ditemukan: " . $item->getInfoSingkat() . "\n";
    }
} else {
    echo "Media tidak ditemukan.\n";
}

echo "\n";

$keyword2 = "Naruto";
echo "Mencari dengan kata kunci: '{$keyword2}'\n";
$hasil2 = $katalog->cariMedia($keyword2);
if (!empty($hasil2)) {
    foreach ($hasil2 as $item) {
        echo "Ditemukan: " . $item->getInfoSingkat() . "\n";
    }
} else {
    echo "Media tidak ditemukan.\n";
}

echo "\n";

// 4. Menampilkan detail media (Polymorphism)
echo "-----------------------------------------\n";
echo "4. Detail Media (Polymorphism):\n";
echo "-----------------------------------------\n";
$anime1->tampilkanDetail();
echo "\n";
$manga1->tampilkanDetail();
echo "\n";
$drama1->tampilkanDetail();
echo "\n";

// 5. Koleksi pribadi pengguna (Watchlist)
echo "-----------------------------------------\n";
echo "5. Koleksi Pengguna (User Watchlist):\n";
echo "-----------------------------------------\n";
$user = new User("Rizqi Maulana", "rizqi@student.umm.ac.id");
echo "Nama User  : " . $user->getNama() . "\n";
echo "Email User : " . $user->getEmail() . "\n\n";

echo "Menambahkan media ke koleksi...\n";
$user->tambahKoleksi($anime1, "Sedang Ditonton");
$user->tambahKoleksi($manga1, "Sedang Dibaca");
$user->tambahKoleksi($drama1, "Selesai Ditonton");

echo "\nDaftar Koleksi {$user->getNama()}:\n";
$user->tampilkanKoleksi();

echo "\nMenghapus 'Attack on Titan' dari koleksi...\n";
$user->hapusKoleksi("Attack on Titan");

echo "\nDaftar Koleksi setelah dihapus:\n";
$user->tampilkanKoleksi();

echo "\n=========================================\n";
echo "Demo Modul 3 Selesai.\n";
echo "=========================================\n";
