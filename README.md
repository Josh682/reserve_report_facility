# Sistem Reservasi & Pelaporan Fasilitas Kampus (Reserve Report)

[![Laravel](https://img.shields.io/badge/Laravel-11.x%20%2F%2012.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

Aplikasi web terintegrasi untuk pengelolaan peminjaman fasilitas kampus (ruang kelas, aula, laboratorium, alat, lapangan) serta pelaporan dan resolusi kerusakan fasilitas secara transparan dan akuntabel. Proyek ini dikembangkan untuk memenuhi tugas mata kuliah **Pengembangan Perangkat Lunak Berorientasi Komponen (PPK) 2026**.

Aplikasi ini bersifat **100% mandiri (self-contained)** dan dirancang untuk berjalan di lingkungan lokal (*on-premise*) tanpa ketergantungan pada layanan cloud pihak ketiga seperti Supabase atau Firebase.

---

## 🚀 Fitur Utama

### 1. Modul Fasilitas & Ketersediaan Publik (US 1, US 2)
- **Katalog Fasilitas**: Pencarian berbasis server dengan filter kategori tipe fasilitas, lokasi gedung, kapasitas ruangan, dan status operasional.
- **Jadwal Ketersediaan Interaktif**: Jadwal visual slot waktu (07.00 – 20.00 WIB) dengan interval tetap 30 menit.
- **Indikator Status Fasilitas**: Informasi ketersediaan real-time (`aktif`, `dalam_perbaikan`, atau `nonaktif`).

### 2. Modul Reservasi Fasilitas (US 3, US 4, US 9, US 10)
- **Filter Jam Dinamis (Client-side)**: Dropdown jam mulai otomatis menyaring slot waktu yang kosong via API ketersediaan, serta membatasi pilihan jam selesai secara kontigu tanpa menabrak jadwal berikutnya.
- **Pengecekan Bentrok Jadwal (Server-side & Concurrency)**: Validasi jadwal tumpang tindih menggunakan transaksi database berproteksi `lockForUpdate()`.
- **Auto-Reject Antrean Bertabrakan**: Ketika petugas menyetujui satu permohonan reservasi, permohonan `pending` lain yang jadwalnya bertabrakan otomatis ditolak dengan catatan audit yang jelas.
- **Pembatalan Mandiri Maksimal H-1**: Pemohon dapat membatalkan reservasi miliknya maksimal 1 hari sebelum tanggal penggunaan (`ASSUMPTION.md` 1.2).
- **Pembatalan Darurat Petugas**: Pembatalan jadwal yang sudah disetujui jika terjadi kondisi darurat/pemeliharaan mendadak dengan kewajiban mengisi alasan audit minimal 10 karakter.
- **Prioritas Antrean Dosen**: Peninjauan permohonan dari akun dosen otomatis diprioritaskan di baris teratas antrean petugas.

### 3. Modul Pelaporan & Resolusi Kerusakan (US 5, US 6, US 7, US 8, US 11, US 12)
- **Formulir Laporan Kerusakan Interaktif**: Validasi ukuran (maks. 2 MB) dan format gambar (`jpeg`, `jpg`, `png`), *live photo preview*, serta penghitung karakter deskripsi interaktif (5 – 2000 karakter).
- **Riwayat Pelaporan Pengguna**: Filter tab status laporan (`semua`, `baru`, `diproses`, `selesai`, `ditolak`), pencarian kata kunci, badge counter, dan preview modal bukti foto.
- **Antrean & Manajemen Tindak Lanjut Petugas**: Kartu penanganan laporan dengan input catatan resolusi teknisi serta opsi sinkronisasi status fasilitas (`dalam_perbaikan` atau kembali `aktif`).
- **Widget KPI Operasional**: Ringkasan antrean kerusakan dan statistik operasional di dashboard Pengguna dan Petugas.

### 4. Modul Admin & Rekapitulasi (US 13, US 14, US 15, US 16, US 17)
- **Manajemen Pengguna & Verifikasi Registrasi**: Akun registrasi mandiri berstatus `pending` dan memerlukan verifikasi (approve/reject) oleh Admin sebelum dapat login.
- **Master Data Fasilitas**: Manajemen penuh data fasilitas dengan proteksi integritas referensial (`restrictOnDelete`).
- **Rekapitulasi Okupansi & Frekuensi Kerusakan**: Perhitungan jam pemakaian aktual (hanya reservasi `approved`) dan statistik kerusakan lintas fasilitas.
- **Ekspor Multi-Format**: Ekspor data rekapitulasi ke format **CSV**, **Excel** (tabel spreadsheet), dan **Print PDF** (layout siap cetak).

### 5. UI/UX & Aksesibilitas
- **Frosted Glassmorphism & Dark Teal Theme**: Tampilan modern dengan komponen glassmorphic, blur ambient, dan tipografi tegas (`DESIGN.md`).
- **Dark Mode / Light Mode**: Toggle tema instan bebas kedipan (*Zero-FOUC*) yang tersinkronisasi via Cookie dan LocalStorage.
- **Navigasi Responsif**: *Off-canvas drawer navigation* (z-50) yang optimal di perangkat mobile maupun desktop.
- **Pintasan Keyboard**: Tekan `N` untuk membuka formulir reservasi/laporan baru dan `ESC` untuk menutup formulir.

---

## 🛠️ Tech Stack & Arsitektur

- **Backend**: PHP 8.2+ / Laravel 11.x / 12.x
- **Frontend**: Blade Templates, Tailwind CSS v3, Vanilla JavaScript ES6+
- **Bundler**: Vite
- **Database**: MySQL 8.x (atau SQLite lokal untuk pengujian cepat)
- **Arsitektur**: Monolithic MVC murni dengan otorisasi berbasis Session & Middleware peran (`admin`, `petugas`, `pengguna`).

---

## 💻 Panduan Menjalankan Proyek di Device Lain (Tanpa Supabase)

Proyek ini menggunakan database lokal mandiri dan **tidak memerlukan koneksi ke Supabase atau layanan cloud database eksternal apa pun**. Ikuti langkah-langkah instalasi berikut:

### 1. Prasyarat Sistem
Pastikan perangkat Anda telah terpasang:
- **PHP >= 8.2** beserta ekstensi: `pdo`, `pdo_mysql` (atau `pdo_sqlite`), `mbstring`, `openssl`, `xml`, `curl`, `fileinfo`.
- **Composer** (PHP Package Manager)
- **Node.js >= 18.x** & **npm**
- **DBMS Lokal**: MySQL 8.x (via XAMPP, Laragon, Homebrew, Docker, atau native) **ATAU** SQLite (tanpa perlu install service tambahan).

---

### 2. Langkah Instalasi Step-by-Step

#### Langkah 1: Clone Repository
```bash
git clone https://github.com/Josh682/reserve_report_facility.git
cd reserve_report_facility
```

#### Langkah 2: Install Dependensi PHP
```bash
composer install
```

#### Langkah 3: Konfigurasi File Lingkungan (`.env`)
Salin file template konfigurasi:
```bash
cp .env.example .env
```
Generate application encryption key:
```bash
php artisan key:generate
```

---

### 3. Konfigurasi Database Lokal (Pilih Salah Satu Opsi)

Pilih salah satu konfigurasi database lokal di bawah ini pada file `.env`:

#### 👉 Opsi A: Menggunakan MySQL Lokal (Direkomendasikan / Standar Proyek)
1. Nyalakan service MySQL Anda (misal: jalankan Apache & MySQL di XAMPP / Laragon).
2. Buat database baru di MySQL (misal via phpMyAdmin atau terminal):
   ```sql
   CREATE DATABASE db_campus_reserve_report CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Sesuaikan baris konfigurasi database pada berkas `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_campus_reserve_report
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Sesuaikan `DB_PASSWORD` jika MySQL lokal Anda menggunakan password).*

#### 👉 Opsi B: Menggunakan SQLite Lokal (Zero-Setup / Paling Praktis untuk Testing)
Jika Anda tidak ingin menginstall atau menyalakan XAMPP/MySQL server, Anda dapat menggunakan SQLite yang berjalan langsung di atas file lokal:
1. Buat file database kosong di direktori `database/`:
   - **Linux / macOS**:
     ```bash
     touch database/database.sqlite
     ```
   - **Windows (PowerShell)**:
     ```powershell
     New-Item database/database.sqlite
     ```
2. Ubah konfigurasi di file `.env` menjadi:
   ```env
   DB_CONNECTION=sqlite
   # DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD dapat dikosongkan atau diabaikan
   ```

---

### 4. Migrasi & Seeding Data Dummy

Jalankan perintah migration untuk membangun seluruh tabel beserta data awal (*User, Fasilitas lengkap per tipe, dan Skenario Laporan Kerusakan*):
```bash
php artisan migrate --seed
```

> **Alternatif Menggunakan File SQL Dump:**  
> Anda juga dapat langsung mengimpor file dump SQL siap pakai yang tersedia di [`database/ppk2026_reservasi_fasilitas.sql`](database/ppk2026_reservasi_fasilitas.sql) ke dalam database MySQL Anda.

---

### 5. Hubungkan Storage Simbolik (Wajib untuk Foto Kerusakan)

Buat symbolic link dari folder storage ke public agar foto bukti laporan kerusakan dapat tampil di browser:
```bash
php artisan storage:link
```

---

### 6. Kompilasi Aset Frontend (Tailwind & JavaScript)

Install modul node dan lakukan build aset Vite:
```bash
npm install
npm run build
```
*(Atau jalankan `npm run dev` jika ingin melakukan pengubahan kode secara hot-reload).*

---

### 7. Jalankan Server Aplikasi

Jalankan server pengembangan Laravel:
```bash
php artisan serve
```
Buka browser dan akses aplikasi pada alamat: **`http://127.0.0.1:8000`** atau **`http://localhost:8000`**.

---

## 👥 Kredensial Akun Pengujian (Demo Accounts)

Database Seeder telah menyediakan akun siap pakai untuk seluruh tingkatan role (seluruh password default adalah: **`password`**):

| Role | Email | Password | Keterangan Akses |
|---|---|---|---|
| **Admin** | `admin@kampus.test` | `password` | Akses penuh dashboard admin, verifikasi registrasi user, kelola fasilitas, rekap & ekspor okupansi. |
| **Petugas** | `petugas@kampus.test` | `password` | Akses dashboard petugas, verifikasi antrean reservasi, pembatalan darurat, tindak lanjut laporan kerusakan. |
| **Pengguna (Mahasiswa)** | `mahasiswa@kampus.test` | `password` | Akun aktif terverifikasi; dapat langsung mengajukan reservasi dan membuat laporan kerusakan. |
| **Calon Pengguna (Pending)** | `pending@kampus.test` | `password` | Akun baru registrasi mandiri (status pending); digunakan untuk menguji alur verifikasi admin. |

---

## 🧪 Pengujian & Analisis Sistem

### Menjalankan Automated Tests
Proyek ini dilengkapi dengan unit dan feature tests menggunakan **Pest**:
```bash
# Menjalankan seluruh test suite
php artisan test

# Menjalankan unit test JavaScript untuk frontend reservation schedule
node --test tests/Unit/ReservationSchedule.test.js
```

### Ekspor Database SQL Terbaru
Tersedia perintah Artisan kustom untuk membuat salinan file dump SQL terstruktur:
```bash
php artisan db:export-sql
```
File akan otomatis tersimpan di `database/ppk2026_reservasi_fasilitas.sql`.

### Analisis Edge Cases
Analisis mendalam mengenai penanganan kondisi batas, konkurensi, dan validasi keamanan dapat dibaca pada dokumen:
👉 [`edge_case.md`](edge_case.md)

---

## 📁 Struktur Direktori Penting

```
├── app/
│   ├── Console/Commands/ExportDatabaseSql.php   # Artisan command ekspor dump database
│   ├── Http/Controllers/
│   │   ├── Admin/                              # FacilityController, UserController, RekapController
│   │   ├── Petugas/                            # ReservationController, ReportController
│   │   ├── Auth/                               # Login & Register Controller
│   │   ├── ReservationController.php           # Alur peminjaman & pembatalan pengguna
│   │   ├── ReportController.php                # Alur pelaporan kerusakan fasilitas
│   │   └── PublicFacilityController.php        # Katalog & API ketersediaan jadwal
│   ├── Http/Middleware/EnsureUserRole.php      # Proteksi otorisasi multi-role
│   ├── Http/Requests/                          # FormRequest server-side validations
│   └── Models/                                 # User, Facility, Reservation, Report
├── database/
│   ├── migrations/                             # Skema DDL tabel database
│   ├── seeders/                                # UserSeeder, FacilitySeeder, ReportSeeder
│   └── ppk2026_reservasi_fasilitas.sql         # Berkas dump database SQL mandiri
├── resources/
│   ├── js/
│   │   └── reservation-schedule.js             # Filter dinamis & boundary jam ketersediaan
│   └── views/                                  # Blade views (Frosted Glassmorphism layouts)
├── routes/web.php                              # Pemetaan rute aplikasi
├── tests/                                      # Feature & Unit Pest tests
└── edge_case.md                                # Matriks analisis edge cases aplikasi
```

---

## 👨‍💻 Kontributor Tim (PPK 2026)

| Nama | NIM | Peran & Modul Utama |
|---|---|---|
| **Joshua Satria Kusuma** | 24060124130113 | Ketua Tim — Arsitektur Sistem, Database, Modul Pelaporan Kerusakan |
| **Iza Yunus Andhika** | 24060124140153 | Pengembang — Modul Reservasi & Desain Antarmuka |
| **Novelya Cherina** | 24060124140174 | Pengembang — Modul Reservasi & Jadwal Ketersediaan |
| **Menza Isaiah Tampubolon** | 24060124140138 | Pengembang — Modul Pelaporan Kerusakan & Integrasi Database |
