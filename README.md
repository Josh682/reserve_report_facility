# Sistem Reservasi & Pelaporan Fasilitas Kampus

Aplikasi web untuk mengelola reservasi fasilitas kampus (ruang kelas, aula, laboratorium, alat,
lapangan) serta pelaporan kerusakan fasilitas. Dibangun dengan Laravel sebagai tugas Project PPK
2026 (Sebelum UTS).

## Tech Stack

- PHP 8.2+ / Laravel 11.x
- MySQL 8.x
- Blade Templates
- Tailwind CSS untuk styling

## Struktur Folder

```
/app              -> Models, Controllers, business logic
/public           -> Entry point aplikasi, assets statis
/resources/views  -> Blade templates (tampilan)
/config           -> Konfigurasi aplikasi Laravel + config custom
/database/migrations -> Skema database
/routes           -> Definisi route (web.php)
```

## Aktor & Role

| Role | Deskripsi |
|---|---|
| Pengunjung | Lihat daftar fasilitas & ketersediaan, tanpa login |
| Pengguna | Mahasiswa/dosen/staf — bisa reservasi & lapor kerusakan |
| Petugas | Proses reservasi & laporan, update status fasilitas |
| Admin | Kelola data master, akun, rekap, verifikasi registrasi |

## Prasyarat

- PHP >= 8.2 beserta ekstensi wajib Laravel (mbstring, openssl, pdo_mysql, tokenizer, xml, ctype, json)
- Composer
- MySQL 8.x (server lokal via XAMPP/MAMP/Homebrew/native — pastikan semua anggota tim pakai versi
  MySQL 8.x, jangan campur dengan 5.7)
- Node.js & npm (jika menggunakan Vite untuk asset build)
- Git

## Instalasi & Setup

1. **Clone repository**
   ```bash
   git clone <url-repo-github>
   cd <nama-folder-project>
   ```

2. **Install dependency PHP**
   ```bash
   composer install
   ```

3. **Salin file environment**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi database di `.env`**

   Sesuaikan nilai berikut dengan setup MySQL lokal masing-masing:
   ```
   DB_DATABASE=ppk2026_reservasi_fasilitas
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Buat database kosong dengan nama yang sama di MySQL sebelum lanjut ke langkah berikutnya
   (`CREATE DATABASE ppk2026_reservasi_fasilitas;`).

6. **Jalankan migration (+ seeder untuk akun dummy tiap role)**
   ```bash
   php artisan migrate --seed
   ```

7. **Buat symbolic link storage** (wajib untuk fitur upload foto laporan kerusakan)
   ```bash
   php artisan storage:link
   ```

8. **Install dependency frontend (jika pakai Vite/Tailwind)**
   ```bash
   npm install
   npm run build
   ```

9. **Jalankan development server**
   ```bash
   php artisan serve
   ```
   Aplikasi bisa diakses di `http://localhost:8000`.

## Menjalankan Ulang dari Awal (Reset Database)

Kalau skema database berubah setelah `git pull`, jalankan:
```bash
php artisan migrate:fresh --seed
```
Perintah ini akan menghapus semua tabel dan membuat ulang dari migration terbaru — **jangan
jalankan di data yang belum di-backup**.

## Konvensi Tim

- Nama tabel & kolom database: **snake_case**, huruf kecil semua (mengikuti konvensi Laravel dan
  menghindari isu case-sensitivity antar OS — lihat `ASSUMPTIONS.md`)
- Branch: jangan push langsung ke `main`, buat branch per fitur (`feature/nama-fitur`), merge lewat
  Pull Request
- Commit message: jelas dan deskriptif, gunakan `Closes #<nomor-issue>` jika menyelesaikan task di
  GitHub Project

## Dokumen Terkait

- `ASSUMPTIONS.md` — asumsi bisnis dan pertanyaan terbuka terkait requirement
- GitHub Project board — task list dan progress tracking tim

## Anggota Tim

| Nama | NIM | Modul |
|---|---|---|
| _Joshua Satria Kusuma_ | _24060124130113_ | Ketua — Setup repo & database |
| _Iza Yunus Andhika_ | _24060124140153_ | Modul Reservasi |
| _Novelya Cherina_ | _24060124140174_ | Modul Reservasi |
| _Joshua Satria Kusuma_ | _24060124130113_ | Modul Laporan Kerusakan |
| _Menza Isaiah Tampubolon_ | _24060124140138_ | Modul Laporan Kerusakan |
