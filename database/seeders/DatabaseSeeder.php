<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Data User
        $user1 = User::create([
            'name' => 'Rizqi Maulana',
            'email' => 'rizqi@student.umm.ac.id',
            'password' => Hash::make('password123'),
        ]);

        $user2 = User::create([
            'name' => 'Armeuu',
            'email' => 'ahmadrizqimaulanaa@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        // 2. Data Media (dari FlipPlay Modul 3)
        $aot = Media::create([
            'judul' => 'Attack on Titan',
            'tipe' => 'Anime',
            'genre' => 'Action, Mystery',
            'rating' => 8.9,
            'tahun' => 2013,
            'status' => 'Selesai',
            'episode' => 89,
            'studio' => 'WIT Studio & MAPPA',
            'deskripsi' => 'Umat manusia terkurung di dalam dinding untuk melindungi diri dari Titan pemakan manusia.',
        ]);

        $frieren = Media::create([
            'judul' => "Frieren: Beyond Journey's End",
            'tipe' => 'Anime',
            'genre' => 'Adventure, Fantasy',
            'rating' => 9.1,
            'tahun' => 2023,
            'status' => 'Selesai',
            'episode' => 28,
            'studio' => 'Madhouse',
            'deskripsi' => 'Perjalanan Frieren seorang penyihir elf yang memahami arti waktu dan kemanusiaan.',
        ]);

        $onePiece = Media::create([
            'judul' => 'One Piece',
            'tipe' => 'Manga',
            'genre' => 'Action, Adventure',
            'rating' => 9.2,
            'tahun' => 1997,
            'status' => 'Publishing',
            'chapter' => 1115,
            'penulis' => 'Eiichiro Oda',
            'deskripsi' => 'Petualangan Monkey D. Luffy dan krunya mengarungi lautan mencari harta karun One Piece.',
        ]);

        $berserk = Media::create([
            'judul' => 'Berserk',
            'tipe' => 'Manga',
            'genre' => 'Dark Fantasy',
            'rating' => 9.4,
            'tahun' => 1989,
            'status' => 'Publishing',
            'chapter' => 376,
            'penulis' => 'Kentaro Miura',
            'deskripsi' => 'Kisah tragis dan perjuangan hidup Guts si Pendekar Pedang Hitam melawan takdir kejam.',
        ]);

        $cloy = Media::create([
            'judul' => 'Crash Landing on You',
            'tipe' => 'Drama',
            'genre' => 'Romance, Comedy',
            'rating' => 8.9,
            'tahun' => 2019,
            'status' => 'Selesai',
            'episode' => 16,
            'network' => 'tvN',
            'deskripsi' => 'Kisah cinta antara pewaris konglomerat Korea Selatan dan kapten tentara Korea Utara.',
        ]);

        // 3. Menambahkan Koleksi Awal ke User 1 (Watchlist)
        $user1->media()->attach([
            $aot->id => ['status' => 'Sedang Ditonton', 'catatan' => 'Sangat seru, sampai season 4'],
            $onePiece->id => ['status' => 'Sedang Dibaca', 'catatan' => 'Arc Egghead'],
            $cloy->id => ['status' => 'Selesai Ditonton', 'catatan' => 'Ending yang mengharukan'],
        ]);
    }
}
