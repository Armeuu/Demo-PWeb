# FlipPlay REST API - Demo Modul 4 Pemrograman Web

RESTful API untuk sistem katalog dan watchlist **FlipPlay**, dikembangkan menggunakan **Laravel 12** sebagai kelanjutan dari simulasi OOP berbasis terminal pada Modul 3.

---

## 📌 Ringkasan Implementasi

| Komponen | Implementasi di FlipPlay |
|---|---|
| **Framework** | Laravel 12 |
| **Entitas Utama** | `Media` (Anime, Manga, Drama) dan `User` (Pengguna) |
| **Relasi Eloquent** | `belongsToMany` (Many-to-Many) antara `User` dan `Media` via tabel pivot `media_user` |
| **Validasi Input** | `StoreMediaRequest` & `AddMediaToCollectionRequest` (Form Request) |
| **Format Response** | `MediaResource` & `UserResource` (API Resource dengan `whenLoaded` & `whenPivotLoaded`) |
| **Error & Status Code** | 200 (OK), 201 (Created), 404 (Not Found), 409 (Conflict), 422 (Unprocessable Entity) |
| **Konsistensi Data** | `DB::transaction()` & `lockForUpdate()` untuk mencegah *Race Condition* pada penambahan koleksi |

---

## 🗄️ Skema Database & Relasi

### 1. Tabel `media`
Menampung data katalog Anime, Manga, dan Drama (mempertahankan struktur Modul 3):
- `id` (Primary Key)
- `judul` (string, unique)
- `tipe` (string: Anime, Manga, Drama)
- `genre` (string, nullable)
- `rating` (decimal 3,1)
- `tahun` (integer)
- `status` (string, default: Selesai)
- `episode` (integer, nullable - khusus Anime & Drama)
- `chapter` (integer, nullable - khusus Manga)
- `studio` (string, nullable - khusus Anime)
- `penulis` (string, nullable - khusus Manga)
- `network` (string, nullable - khusus Drama)
- `deskripsi` (text, nullable)
- `timestamps`

### 2. Tabel `users`
Menampung data pengguna perpustakaan / media:
- `id` (Primary Key)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `timestamps`

### 3. Tabel Pivot `media_user`
Menghubungkan `User` dengan `Media` (Koleksi / Watchlist pribadi):
- `id` (Primary Key)
- `user_id` (foreignId constrained cascade)
- `media_id` (foreignId constrained cascade)
- `status` (string, default: 'Sedang Ditonton')
- `catatan` (text, nullable)
- `timestamps`
- Unique constraint: `['user_id', 'media_id']`

---

## 🚀 Endpoint API

Semua endpoint didaftarkan di `routes/api.php`:

### 1. Media (CRUD Standar)
| Method | Endpoint | Fungsi | Status Code |
|---|---|---|---|
| `GET` | `/api/media` | Menampilkan seluruh media (mendukung query `?search=...`, `?tipe=...`, dan pagination) | 200 OK |
| `POST` | `/api/media` | Menambahkan media baru ke sistem | 201 Created |
| `GET` | `/api/media/{media}` | Menampilkan detail 1 media beserta pengguna yang mengoleksinya | 200 OK / 404 |
| `PATCH` | `/api/media/{media}` | Memperbarui data media | 200 OK / 422 / 404 |
| `DELETE`| `/api/media/{media}` | Menghapus media dari katalog | 200 OK / 404 |

### 2. Users & Watchlist (Custom Actions & Relasi)
| Method | Endpoint | Fungsi | Status Code |
|---|---|---|---|
| `GET` | `/api/users` | Menampilkan daftar seluruh user dan jumlah koleksinya | 200 OK |
| `POST` | `/api/users` | Menambahkan user baru | 201 Created |
| `GET` | `/api/users/{user}` | Menampilkan profil user beserta seluruh koleksi medianya (Eager Loading) | 200 OK / 404 |
| `GET` | `/api/users/{user}/collection` | Menampilkan hanya daftar media yang ada di koleksi user | 200 OK |
| `POST` | `/api/users/{user}/collection` | **Custom Action**: Menambahkan media ke watchlist (mencegah duplikat 409 & race condition) | 201 Created / 409 / 422 |
| `PATCH` | `/api/users/{user}/collection/{media}` | **Custom Action**: Mengubah status koleksi (misal dari "Sedang Ditonton" ke "Selesai Ditonton") | 200 OK / 404 |
| `DELETE`| `/api/users/{user}/collection/{media}` | **Custom Action**: Menghapus media dari watchlist pribadi user (detach) | 200 OK / 404 |

---

## 🛠️ Langkah Instalasi & Pengujian

### 1. Setup Environment & Database
Buka file `.env` dan pastikan konfigurasi database sesuai dengan lingkungan Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flipplay
DB_USERNAME=root
DB_PASSWORD=
```
*(Catatan: SQLite juga didukung penuh untuk pengujian cepat dengan `DB_CONNECTION=sqlite`).*

### 2. Menjalankan Migrasi dan Seeder
```bash
php artisan migrate:fresh --seed
```
Perintah ini akan membuat struktur tabel dan mengisikan data awal:
- Pengguna: **Rizqi Maulana** (`rizqi@student.umm.ac.id`)
- Media: *Attack on Titan*, *Frieren*, *One Piece*, *Berserk*, *Crash Landing on You*.
- Koleksi awal: Attack on Titan ("Sedang Ditonton"), One Piece ("Sedang Dibaca"), Crash Landing on You ("Selesai Ditonton").

### 3. Menjalankan Server Development
```bash
php artisan serve
```
Server berjalan di `http://127.0.0.1:8000`.

### 4. Menjalankan Unit & Feature Test Otomatis
```bash
php artisan test
```
Seluruh 19 unit & feature tests mencakup seluruh skenario validasi, status code, dan relasi.

---

## 🧪 Pengujian via Postman

File koleksi Postman siap pakai telah disediakan di root project:
📁 `FlipPlay_API_Postman_Collection.json`

Cukup buka **Postman** -> Klik **Import** -> Pilih file `FlipPlay_API_Postman_Collection.json`.

Koleksi tersebut berisi skenario lengkap:
1. **Happy Path**:
   - `GET /api/media`
   - `GET /api/media?search=Piece`
   - `GET /api/media?tipe=Anime`
   - `POST /api/media` (201 Created)
   - `GET /api/media/1` (200 OK)
   - `PATCH /api/media/1` (200 OK)
   - `DELETE /api/media/5` (200 OK)
   - `GET /api/users/1` (200 OK)
   - `POST /api/users/1/collection` (201 Created)
   - `PATCH /api/users/1/collection/1` (200 OK)
   - `DELETE /api/users/1/collection/1` (200 OK)
2. **Failure Path**:
   - `POST /api/media` (Field kosong -> 422 Unprocessable Entity)
   - `POST /api/media` (Judul duplikat -> 422 Unprocessable Entity)
   - `GET /api/media/9999` (ID tidak ditemukan -> 404 Not Found)
   - `POST /api/users/1/collection` (Media sudah ada di koleksi -> 409 Conflict)
   - `POST /api/users/1/collection` (Media ID invalid -> 422 Unprocessable Entity)
   - `DELETE /api/users/1/collection/9999` (Media tidak ada di koleksi -> 404 Not Found)
