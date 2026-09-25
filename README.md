# Activity Manager v1

Proyek 3 - Modul 3: Frameworks in Programming (Laravel Basic)
D3 Teknik Informatika - Muhammad Hilmi Farahat (251511049)

Aplikasi manajemen kegiatan sederhana menggunakan Laravel: CRUD kegiatan,
validasi input via Form Request, dan aturan transisi status
(Planned → Ongoing → Done) yang ditegakkan melalui service class.

## Requirement

- PHP 8.3 atau lebih baru
- Composer 2.x
- SQLite (bawaan PHP, tidak perlu instalasi server database terpisah)

## Cara Menjalankan

1. Clone repository:

git clone https://github.com/mielnial/modul3proyek.git
cd modul3proyek


2. Install dependency:

composer install


3. Salin file environment:

copy .env.example .env

   (di Linux/Mac pakai `cp .env.example .env`)

4. Generate application key:

php artisan key:generate


5. Buat file database SQLite kosong (kalau belum ada):

type nul > database\database.sqlite

   (di Linux/Mac: `touch database/database.sqlite`)

6. Jalankan migration sekaligus seeder:

php artisan migrate --seed


7. Jalankan server:

php artisan serve


8. Buka `http://127.0.0.1:8000/activities` di browser.

## Fitur

- Daftar, detail, tambah, ubah, hapus kegiatan (CRUD)
- Validasi input (judul 5-100 karakter, tanggal wajib, status harus salah satu
  dari Planned/Ongoing/Done) melalui Form Request
- Aturan transisi status (tidak boleh mundur) melalui `ActivityService`
- Filter daftar kegiatan berdasarkan status via query string (`?status=Planned`)

## Route Utama

| Method | URL | Keterangan |
|---|---|---|
| GET | /activities | Daftar kegiatan (bisa difilter `?status=`) |
| GET | /activities/create | Form tambah kegiatan |
| POST | /activities | Simpan kegiatan baru |
| GET | /activities/{activity} | Detail kegiatan |
| GET | /activities/{activity}/edit | Form ubah kegiatan |
| PUT | /activities/{activity} | Simpan perubahan kegiatan |
| DELETE | /activities/{activity} | Hapus kegiatan |

## Struktur Penting

- `app/Http/Controllers/ActivityController.php` - orkestrasi request/response
- `app/Http/Requests/` - validasi input (StoreActivityRequest, UpdateActivityRequest)
- `app/Services/ActivityService.php` - business logic transisi status
- `app/Models/Activity.php` - Eloquent model + daftar status yang sah
- `database/seeders/ActivitySeeder.php` - data awal untuk observasi