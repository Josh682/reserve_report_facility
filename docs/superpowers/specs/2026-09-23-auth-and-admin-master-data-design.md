# Desain Spesifikasi: Modul Auth & Admin Master Data

> **Status:** Approved  
> **Tanggal:** 2026-09-23  
> **Branch:** `feature/auth-admin-master-data`  
> **GitHub Epic:** Issue #3 (`Modul Auth & Admin Master Data`)  
> **Sub-Issues Terkait:**  
> - Issue #16: Implementasi registrasi pengguna mandiri (sudah selesai dikerjakan)  
> - Issue #17: Implementasi login/logout untuk semua role  
> - Issue #18: Implementasi middleware pembatasan akses per role  
> - Issue #19: Admin: CRUD data fasilitas (US 16)  
> - Issue #20: Admin: form pendaftaran akun petugas langsung (US 13)  
> - Issue #21: Admin: form pendaftaran akun pengguna langsung (US 14)  
> - Issue #22: Admin: verifikasi/tolak akun hasil registrasi mandiri (US 15)  

---

## 1. Konteks & Tujuan

Modul ini bertujuan melengkapi sistem autentikasi dan manajemen master data untuk aplikasi Sistem Reservasi & Pelaporan Fasilitas Kampus. Fondasi registrasi mandiri pengguna (Issue #16) telah selesai diimplementasikan. Tahapan saat ini adalah menyelesaikan:
1. Alur login dan logout yang aman untuk semua role (`admin`, `petugas`, `pengguna`).
2. Aturan validasi status akun saat login sesuai dokumen `ASSUMPTION.md` (hanya akun `verified` yang dapat masuk, akun `pending` atau `rejected` dicegah).
3. Middleware otorisasi berbasis role untuk melindungi rute aplikasi.
4. Pengelolaan master data fasilitas kampus oleh Admin (US 16).
5. Pengelolaan verifikasi akun mandiri (US 15) dan pembuatan akun langsung untuk petugas (US 13) dan pengguna (US 14) oleh Admin.

---

## 2. Batasan Global & Arsitektur

- **Framework:** Laravel 11.x, PHP 8.5, Blade Templates, Tailwind CSS, Pest Testing Framework.
- **Skema Basis Data yang Digunakan:**
  - `users`: `id`, `name`, `email`, `password`, `role` (`pengguna`, `petugas`, `admin`), `tipe_pengguna` (`mahasiswa`, `dosen`, `staf`), `status_akun` (`pending`, `verified`, `rejected`), timestamps.
  - `facilities`: `id`, `nama`, `tipe` (`ruang_kelas`, `aula`, `laboratorium`, `alat`, `lapangan`), `lokasi`, `kapasitas`, `deskripsi`, `status` (`aktif`, `dalam_perbaikan`, `nonaktif`), timestamps.
  - `reservations`: `id`, `user_id`, `facility_id`, ..., timestamps.
- **Konvensi Kode:**
  - Strict typing, explicit return types pada PHP class & methods.
  - Menggunakan Form Request untuk validasi HTTP input.
  - Menggunakan middleware Laravel untuk otorisasi akses route.
  - Konsistensi styling menggunakan utility Tailwind CSS dengan komponen blade layout modular.

---

## 3. Spesifikasi Rinci Komponen

### 3.1 Sub-Modul Autentikasi & Sesi (Issue #17)

#### Alur Login (`POST /login`)
1. Pengguna memasukkan `email` dan `password` pada form di `resources/views/auth/login.blade.php`.
2. Validasi via `App\Http\Requests\Auth\LoginRequest`:
   - `email`: `['required', 'string', 'email']`
   - `password`: `['required', 'string']`
3. Pengujian kredensial melalui `Auth::attempt($credentials, $remember)`:
   - Jika kombinasi email/password salah: kembalikan error validasi pada field `email` (*"Kredensial yang diberikan tidak cocok dengan data kami."*).
4. **Pengecekan Status Akun (`status_akun`)**:
   - Jika kredensial cocok, cek `$user->status_akun`:
     - Jika `status_akun === 'pending'`: Logout sesi langsung via `Auth::logout()`, batalkan sesi, dan redirect kembali ke `/login` dengan error flash message:  
       `"Akun Anda sedang menunggu verifikasi oleh Admin sebelum dapat digunakan."`
     - Jika `status_akun === 'rejected'`: Logout sesi langsung via `Auth::logout()`, batalkan sesi, dan redirect kembali ke `/login` dengan error flash message:  
       `"Akun Anda telah ditolak oleh Admin. Silakan hubungi bagian administrasi kampus."`
     - Jika `status_akun === 'verified'`: Regenerate session ID (`$request->session()->regenerate()`) dan lanjutkan ke alur redirect.
5. **Redirect Pasca-Login Berbasis Role**:
   - Jika role `admin`: redirect ke route `admin.dashboard` (`/admin/dashboard`).
   - Jika role `petugas`: redirect ke route `petugas.dashboard` (`/petugas/dashboard`).
   - Jika role `pengguna`: redirect ke route home `welcome` (`/`).

#### Alur Logout (`POST /logout`)
1. Route dilindungi middleware `auth`.
2. Controller `App\Http\Controllers\Auth\LoginController::destroy`:
   - Panggil `Auth::logout()`.
   - Invalidate session: `$request->session()->invalidate()`.
   - Regenerate CSRF token: `$request->session()->regenerateToken()`.
   - Redirect ke route `login` dengan flash message sukses.

---

### 3.2 Sub-Modul Middleware Otorisasi Role (Issue #18)

#### Middleware `App\Http\Middleware\EnsureUserRole`
- Alias middleware: `'role'` (didaftarkan di `bootstrap/app.php`).
- Menerima argumen daftar role yang diperbolehkan, misal: `role:admin` atau `role:admin,petugas`.
- Logika pengecekan:
  ```php
  public function handle(Request $request, Closure $next, string ...$roles): Response
  {
      if (! $request->user()) {
          return redirect()->route('login');
      }

      if (! in_array($request->user()->role, $roles, true)) {
          abort(403, 'Anda tidak memiliki hak akses untuk membuka halaman ini.');
      }

      return $next($request);
  }
  ```

---

### 3.3 Sub-Modul Admin Master Data Fasilitas (Issue #19 / US 16)

#### Model `App\Models\Facility`
- File: `app/Models/Facility.php`
- `#[Fillable(['nama', 'tipe', 'lokasi', 'kapasitas', 'deskripsi', 'status'])]`
- Casting: `kapasitas => integer`.
- Relasi: `hasMany(Reservation::class)`.

#### Controller `App\Http\Controllers\Admin\FacilityController`
- `index(Request $request)`:
  - Mendukung filter pencarian `search` (nama/lokasi), filter `tipe`, dan filter `status`.
  - Pagination 10 item per halaman.
- `create()`:
  - Menampilkan form tambah fasilitas (`resources/views/admin/facilities/create.blade.php`).
- `store(FacilityRequest $request)`:
  - Menyimpan fasilitas baru dengan validasi data.
  - Redirect ke `admin.facilities.index` dengan flash status sukses.
- `edit(Facility $facility)`:
  - Menampilkan form edit fasilitas (`resources/views/admin/facilities/edit.blade.php`).
- `update(FacilityRequest $request, Facility $facility)`:
  - Mengupdate data fasilitas.
  - Redirect ke index dengan flash status sukses.
- `updateStatus(Request $request, Facility $facility)`:
  - Aksi cepat ubah status (`PATCH /admin/facilities/{id}/status`).
  - Menerima `status` (`aktif`, `dalam_perbaikan`, `nonaktif`).
- `destroy(Facility $facility)`:
  - Mengecek apakah ada reservasi pada fasilitas:
    - Jika ada relasi di `reservations`: Batalkan penghapusan permanen dan kembalikan pesan peringatan agar admin mengubah status menjadi `nonaktif` demi integritas riwayat.
    - Jika tidak ada reservasi terkait: Hapus record fasilitas.

#### Form Request `App\Http\Requests\Admin\FacilityRequest`
- `nama`: `['required', 'string', 'max:150']`
- `tipe`: `['required', Rule::in(['ruang_kelas', 'aula', 'laboratorium', 'alat', 'lapangan'])]`
- `lokasi`: `['required', 'string', 'max:255']`
- `kapasitas`: `['nullable', 'integer', 'min:1']`
- `deskripsi`: `['nullable', 'string']`
- `status`: `['required', Rule::in(['aktif', 'dalam_perbaikan', 'nonaktif'])]`

---

### 3.4 Sub-Modul Admin Manajemen & Verifikasi Akun (Issues #20, #21, #22 / US 13, 14, 15)

#### Controller `App\Http\Controllers\Admin\UserController`
- `index(Request $request)`:
  - Menampilkan antarmuka terpadu di `resources/views/admin/users/index.blade.php`.
  - Menampilkan 2 Tab:
    1. **Tab 1: Verifikasi Pendaftar Pending (US 15)**: Menampilkan list user dengan `status_akun = 'pending'`.
    2. **Tab 2: Semua Akun Terdaftar (US 13 & 14)**: Menampilkan list user dengan filter role (`admin`, `petugas`, `pengguna`).
- `approve(User $user)` (Issue #22 / US 15):
  - Ubah `$user->status_akun = 'verified'`.
  - Redirect kembali dengan pesan sukses.
- `reject(User $user)` (Issue #22 / US 15):
  - Ubah `$user->status_akun = 'rejected'`.
  - Redirect kembali dengan pesan sukses.
- `create()` (Issue #20 & #21 / US 13 & 14):
  - Menampilkan form pendaftaran akun langsung oleh admin.
- `store(StoreUserRequest $request)` (Issue #20 & #21 / US 13 & 14):
  - Input: `name`, `email`, `role` (`petugas` atau `pengguna`), `tipe_pengguna` (`mahasiswa`, `dosen`, `staf` jika role = pengguna, null jika petugas), `password`.
  - Akun langsung diset `status_akun = 'verified'` otomatis sesuai aturan bisnis `ASSUMPTION.md`.

---

### 3.5 Desain Antarmuka Pengguna (UI/UX)

1. **Admin Layout (`resources/views/layouts/admin.blade.php`)**:
   - Sidebar navigasi modern:
     - Dashboard (`/admin/dashboard`)
     - Master Fasilitas (`/admin/facilities`)
     - Manajemen Pengguna & Verifikasi (`/admin/users`)
     - Logout Button
   - Header bar dengan avatar nama admin dan badge role.
   - Flash message banner otomatis (sukses / error) yang konsisten.
2. **Dashboard Ringkasan Admin (`resources/views/admin/dashboard.blade.php`)**:
   - Kartu statistik: Total Fasilitas Aktif, Fasilitas Dalam Perbaikan, Pendaftar Menunggu Verifikasi.
   - Akses pintas ke antrean verifikasi dan tambah fasilitas.
3. **Komponen Tabel & Form**:
   - Input fields dengan label, error helper inline, dan styling Tailwind senada dengan auth layout yang sudah ada.
   - Badge warna status: Hijau (`aktif`/`verified`), Kuning (`dalam_perbaikan`/`pending`), Merah/Abu (`nonaktif`/`rejected`).

---

## 4. Rencana Pengujian Otomatis (Pest Testing)

Seluruh komponen akan diuji secara mendalam dengan feature test:

1. **`tests/Feature/Auth/LoginTest.php`**:
   - `test('guest can view login page')`
   - `test('verified user can login with valid credentials and redirect based on role')`
   - `test('pending user cannot login and sees pending status warning')`
   - `test('rejected user cannot login and sees rejection notice')`
   - `test('login fails with invalid credentials')`
   - `test('authenticated user can logout and session is cleared')`

2. **`tests/Feature/Auth/RoleMiddlewareTest.php`**:
   - `test('admin can access admin routes')`
   - `test('regular user cannot access admin routes and gets 403')`
   - `test('guest cannot access admin routes and is redirected to login')`

3. **`tests/Feature/Admin/FacilityManagementTest.php`**:
   - `test('admin can view facilities list')`
   - `test('admin can create a new facility with valid data')`
   - `test('facility creation fails with invalid inputs')`
   - `test('admin can update an existing facility')`
   - `test('admin can toggle facility status')`
   - `test('admin can delete a facility without reservation history')`

4. **`tests/Feature/Admin/UserManagementTest.php`**:
   - `test('admin can view pending verification users list')`
   - `test('admin can approve a pending user account')`
   - `test('admin can reject a pending user account')`
   - `test('admin can directly create a petugas account which is automatically verified')`
   - `test('admin can directly create a pengguna account which is automatically verified')`

---

## 5. Rencana Commit Git

Mengikuti konvensi commit tim dengan referensi issue:
1. `feat(auth): implement login and logout with account status verification, closes #17`
2. `feat(auth): add role middleware to protect admin and user routes, closes #18`
3. `feat(facilities): implement Facility model, controller, and admin views, closes #19`
4. `feat(users): implement direct account creation for petugas and pengguna, closes #20, closes #21`
5. `feat(users): implement account verification approval and rejection by admin, closes #22`
6. `feat(admin): create admin dashboard and navigation layout, closes #3`
