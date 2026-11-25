# Demo Modul 3 - Pemrograman Web

Program simulasi sederhana katalog FlipPlay menggunakan PHP OOP untuk memenuhi tugas Demo Modul 3 Praktikum Pemrograman Web.

## Struktur File
```text
flipplay-modul3/
├── src/
│   ├── Media.php    # Parent class (Abstract class)
│   ├── Anime.php    # Child class turunan Media
│   ├── Manga.php    # Child class turunan Media
│   ├── Drama.php    # Child class turunan Media
│   ├── Katalog.php  # Class untuk menampung dan mencari data media
│   └── User.php     # Class untuk data user dan koleksi (watchlist)
├── index.php        # File utama untuk menjalankan program
└── README.md
```

## Penerapan OOP
1. **Class & Object**: Dibuat class Media, Anime, Manga, Drama, Katalog, dan User.
2. **Constructor**: Inisialisasi atribut dengan `__construct()` dan `parent::__construct()`.
3. **Access Modifier**: Menggunakan `private`, `protected`, dan `public`.
4. **Inheritance**: Class `Anime`, `Manga`, dan `Drama` mewarisi properti dari `Media`.
5. **Polymorphism**: Method `tampilkanDetail()` di-override oleh masing-masing class turunan dengan format informasi yang berbeda.
6. **Type Hinting & Return Type**: Digunakan pada parameter dan return value setiap method.
7. **Namespace**: Menggunakan `namespace FlipPlay;`.

## Cara Menjalankan
Buka terminal di folder ini, lalu jalankan:
```bash
php index.php
```
