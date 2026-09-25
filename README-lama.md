# Activity Manager v1

Aplikasi manajemen kegiatan berbasis Laravel — dikembangkan sebagai bagian dari **Proyek 3: Pengembangan Perangkat Lunak Berbasis Web**, Modul 3 (Frameworks in Programming - Laravel Basic), D3 Teknik Informatika, Politeknik Negeri Bandung.

> Repository ini akan dilanjutkan dan dikembangkan pada Modul 4–6.

## Identitas

- **Nama**: Mii
- **NIM**: (isi NIM)
- **Kelas**: 2B
- **Modul**: Modul 3 — Laravel Basic

## Tentang Aplikasi

Activity Manager v1 adalah aplikasi CRUD sederhana untuk mengelola data kegiatan (Planned → Ongoing → Done), dibangun untuk mempelajari:
- Request lifecycle Laravel
- Routing, Controller, Blade
- Migration & Eloquent Model
- Form Request (validasi input)
- Route Model Binding
- Business logic pada Service Class
- Static analysis dengan SonarQube

## Requirement

- PHP 8.3 atau lebih baru
- Composer
- Laravel 13
- SQLite (baseline kelas)
- Git

## Versi Environment yang Digunakan

| Komponen | Versi |
|---|---|
| PHP | 8.3.33 |
| Composer | 2.10.3 |
| Laravel | v13.32.0 |
| OS | Windows |

## Instalasi & Setup

Clone repository:

```bash
git clone <URL_REPO_INI>
cd activity-manager
```

Install dependency:

```bash
composer install
```

Salin file environment lalu generate application key:

```bash
cp .env.example .env
php artisan key:generate
```

Pastikan konfigurasi database di `.env` mengarah ke SQLite (baseline kelas):

```
DB_CONNECTION=sqlite
```

Buat file database SQLite (jika belum ada):

```bash
touch database/database.sqlite
```

Jalankan migration sekaligus seeder:

```bash
php artisan migrate --seed
```

Jalankan development server:

```bash
php artisan serve
```

Buka aplikasi di URL yang ditampilkan (default: `http://127.0.0.1:8000`).

## Struktur Proyek (bagian relevan)

```
activity-manager/
├── app/
│   ├── Http/
│   │   ├── Controllers/ActivityController.php
│   │   └── Requests/
│   │       ├── StoreActivityRequest.php
│   │       └── UpdateActivityRequest.php
│   ├── Models/Activity.php
│   └── Services/ActivityService.php
├── database/
│   ├── migrations/
│   └── seeders/ActivitySeeder.php
├── resources/views/
│   ├── layouts/app.blade.php
│   └── activities/
│       ├── index.blade.php
│       ├── show.blade.php
│       ├── create.blade.php
│       ├── edit.blade.php
│       └── _form.blade.php
├── routes/web.php
├── sonar-project.properties
└── README.md
```

## Route Utama

| Method | URI | Controller | Keterangan |
|---|---|---|---|
| GET | `/activities` | index | Daftar kegiatan |
| GET | `/activities/create` | create | Form tambah kegiatan |
| POST | `/activities` | store | Simpan kegiatan baru |
| GET | `/activities/{activity}` | show | Detail kegiatan |
| GET | `/activities/{activity}/edit` | edit | Form ubah kegiatan |
| PUT/PATCH | `/activities/{activity}` | update | Simpan perubahan kegiatan |
| DELETE | `/activities/{activity}` | destroy | Hapus kegiatan |

Cek daftar route lengkap dengan:

```bash
php artisan route:list
```

## Business Rules Singkat

| ID | Aturan |
|---|---|
| BR-01 | Judul kegiatan wajib diisi, 5–100 karakter |
| BR-02 | Tanggal kegiatan wajib diisi & valid |
| BR-03 | Status hanya boleh: Planned, Ongoing, Done |
| BR-03A | Transisi status hanya maju: Planned → Ongoing → Done (tidak boleh mundur) |

Detail lengkap business rules & acceptance criteria ada di Worksheet Modul 3.

## Static Analysis (SonarQube)

Jalankan scanner (endpoint disediakan dosen/lab):

```bash
sonar-scanner -Dsonar.host.url=<ALAMAT_SONARQUBE> -Dsonar.token=<TOKEN_MAHASISWA>
```

## Formatting

Menjaga konsistensi gaya kode dengan Laravel Pint:

```bash
./vendor/bin/pint
```

## Checkpoint Modul

- [ ] Task 1 — Laravel Skeleton & Request Lifecycle
- [ ] Task 2 — Activity CRUD dengan Form Request
- [ ] Task 3 — Business Logic Refactor & Quality Baseline
- [ ] Independent Challenge — Filter Kegiatan
- [ ] Homework — Architecture Review & Refactor Terarah
- [ ] Checkpoint Integrasi Modul 3 (tag: `modul-3-final`)

## Catatan

Autentikasi, ownership, dan role **belum** diimplementasikan pada Modul 3 — akan ditambahkan pada Modul 4 dan 5.
