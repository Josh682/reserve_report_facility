# Issue #16: Implementasi Registrasi Pengguna — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun fitur registrasi mandiri pengguna (mahasiswa, dosen, staf) dengan validasi ganda (server + client), penetapan role `pengguna` dan `status_akun = 'pending'`, serta notifikasi menunggu verifikasi admin sesuai spesifikasi US #15 & ASSUMPTION.md.

**Architecture:** Menggunakan arsitektur MVC standar Laravel 11. Controller `RegisterController` menangani request yang telah divalidasi oleh `RegisterRequest`. Model `User` di-update untuk mengizinkan atribut `role`, `tipe_pengguna`, dan `status_akun`. Tampilan UI dibangun menggunakan Blade template dengan Tailwind CSS yang responsif dan dilengkapi validasi interaktif di sisi klien.

**Tech Stack:** PHP 8.5, Laravel 11.x, Blade Templates, Tailwind CSS, Pest PHP Testing Framework.

**Spec:** [ASSUMPTION.md](file:///C:/Menza/Projek%20PPk%20-%20reserve_report_facility/ASSUMPTION.md) (Bagian 3.1 & 3.2), GitHub Issue #16.

## Global Constraints

- **Tabel Basis Data:** `users` (sudah dibuat lewat migrasi `2026_09_15_214214_create_users_table.php`).
- **Nilai Default Registrasi Mandiri:** `role = 'pengguna'`, `status_akun = 'pending'`.
- **Pilihan Tipe Pengguna:** `mahasiswa`, `dosen`, `staf`.
- **Keamanan:** Password wajib di-hash menggunakan bcrypt (`Hash::make` atau cast `hashed`).
- **Autologin:** Pengguna **TIDAK** boleh langsung di-login-kan setelah registrasi, karena status akun masih `pending` dan butuh verifikasi admin.
- **Konvensi Kode:** PHP 8.x typed properties & return types, PSR-12, snake_case untuk tabel/kolom database.

---

### Task 1: Update Model User Fillable & Helper Methods

**Files:**
- Modify: `app/Models/User.php`
- Test: `tests/Feature/UserRegistrationTest.php`

**Interfaces:**
- Consumes: Skema tabel `users`
- Produces: Model `User` yang mengizinkan mass-assignment untuk `name`, `email`, `password`, `role`, `tipe_pengguna`, `status_akun`.

- [ ] **Step 1: Tulis test pengecekan mass-assignment model User**

```php
// tests/Feature/UserRegistrationTest.php
test('user model can mass assign role, tipe_pengguna, and status_akun', function () {
    $user = new \App\Models\User([
        'name' => 'Test User',
        'email' => 'test@kampus.test',
        'password' => 'secret123',
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'status_akun' => 'pending',
    ]);

    expect($user->role)->toBe('pengguna')
        ->and($user->tipe_pengguna)->toBe('mahasiswa')
        ->and($user->status_akun)->toBe('pending');
});
```

- [ ] **Step 2: Jalankan test dan pastikan gagal**

Run: `php artisan test --filter=UserRegistrationTest`
Expected: FAIL (atribut tidak masuk fillable)

- [ ] **Step 3: Update `app/Models/User.php`**

Tambahkan `role`, `tipe_pengguna`, dan `status_akun` pada atribut `#[Fillable]`:

```php
#[Fillable(['name', 'email', 'password', 'role', 'tipe_pengguna', 'status_akun'])]
```

- [ ] **Step 4: Jalankan test dan pastikan lolos**

Run: `php artisan test --filter=UserRegistrationTest`
Expected: PASS

- [ ] **Step 5: Commit perubahan Task 1**

```bash
git add app/Models/User.php tests/Feature/UserRegistrationTest.php
git commit -m "feat(user): add role, tipe_pengguna, and status_akun to fillable attributes"
```

---

### Task 2: Form Request Validation (`RegisterRequest`)

**Files:**
- Create: `app/Http/Requests/Auth/RegisterRequest.php`
- Modify: `tests/Feature/UserRegistrationTest.php`

**Interfaces:**
- Consumes: HTTP POST `/register` payload (`name`, `email`, `tipe_pengguna`, `password`, `password_confirmation`)
- Produces: Data tervalidasi yang siap diproses oleh controller.

- [ ] **Step 1: Tulis test validasi data registrasi**

Tambahkan test cases untuk:
1. Field wajib diisi (`name`, `email`, `tipe_pengguna`, `password`).
2. Format email valid dan unik.
3. Tipe pengguna harus salah satu dari `mahasiswa`, `dosen`, `staf`.
4. Password minimal 8 karakter dan harus cocok dengan `password_confirmation`.

- [ ] **Step 2: Jalankan test dan pastikan gagal**

Run: `php artisan test --filter=UserRegistrationTest`
Expected: FAIL (Class RegisterRequest not found)

- [ ] **Step 3: Buat `app/Http/Requests/Auth/RegisterRequest.php`**

```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'tipe_pengguna' => ['required', 'string', Rule::in(['mahasiswa', 'dosen', 'staf'])],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar dalam sistem.',
            'tipe_pengguna.required' => 'Pilih jenis pengguna (Mahasiswa, Dosen, atau Staf).',
            'tipe_pengguna.in' => 'Jenis pengguna tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
```

- [ ] **Step 4: Jalankan test dan pastikan lolos**

Run: `php artisan test --filter=UserRegistrationTest`
Expected: PASS

- [ ] **Step 5: Commit perubahan Task 2**

```bash
git add app/Http/Requests/Auth/RegisterRequest.php tests/Feature/UserRegistrationTest.php
git commit -m "feat(auth): create RegisterRequest with strict validation rules and indonesian messages"
```

---

### Task 3: Controller & Routing (`RegisterController`)

**Files:**
- Create: `app/Http/Controllers/Auth/RegisterController.php`
- Modify: `routes/web.php`
- Modify: `tests/Feature/UserRegistrationTest.php`

**Interfaces:**
- Consumes: `RegisterRequest`
- Produces: 
  - `GET /register`: Menampilkan view `auth.register`
  - `POST /register`: Membuat record user baru dengan `role = 'pengguna'` & `status_akun = 'pending'`, lalu redirect ke halaman login dengan pesan sukses.

- [ ] **Step 1: Tulis test integrasi untuk route GET & POST `/register`**

```php
test('guest can view registration page', function () {
    $response = $this->get(route('register'));
    $response->assertStatus(200);
    $response->assertSee('Daftar Akun');
});

test('guest can register with valid data and receives pending status', function () {
    $userData = [
        'name' => 'Menza Isaiah',
        'email' => 'menza@mahasiswa.test',
        'tipe_pengguna' => 'mahasiswa',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->post(route('register'), $userData);

    $this->assertDatabaseHas('users', [
        'name' => 'Menza Isaiah',
        'email' => 'menza@mahasiswa.test',
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'status_akun' => 'pending',
    ]);

    $this->assertGuest(); // pastikan tidak langsung login
    $response->assertRedirect(route('login'));
    $response->assertSessionHas('status');
});
```

- [ ] **Step 2: Jalankan test dan pastikan gagal**

Run: `php artisan test --filter=UserRegistrationTest`
Expected: FAIL (Route `register` not found)

- [ ] **Step 3: Buat `RegisterController.php` dan daftarkan route di `routes/web.php`**

Implementasikan `RegisterController`:
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'tipe_pengguna' => $request->validated('tipe_pengguna'),
            'password' => Hash::make($request->validated('password')),
            'role' => 'pengguna',
            'status_akun' => 'pending',
        ]);

        return redirect()->route('login')->with(
            'status',
            'Pendaftaran akun berhasil! Akun Anda sedang menunggu verifikasi oleh Admin sebelum dapat digunakan untuk login.'
        );
    }
}
```

Daftarkan route di `routes/web.php`:
```php
use App\Http\Controllers\Auth\RegisterController;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login'); // placeholder route login untuk target redirect
});
```

- [ ] **Step 4: Jalankan test dan pastikan lolos**

Run: `php artisan test --filter=UserRegistrationTest`
Expected: PASS

- [ ] **Step 5: Commit perubahan Task 3**

```bash
git add app/Http/Controllers/Auth/RegisterController.php routes/web.php tests/Feature/UserRegistrationTest.php
git commit -m "feat(auth): add RegisterController and register routes"
```

---

### Task 4: Tampilan Antarmuka UI (`resources/views/auth/register.blade.php`)

**Files:**
- Create: `resources/views/layouts/guest.blade.php` (Layout konsisten)
- Create: `resources/views/auth/register.blade.php`
- Create: `resources/views/auth/login.blade.php` (Minimal target untuk redirect alert)

**Interfaces:**
- Menampilkan formulir registrasi yang rapi, modern, dan responsif dengan Tailwind CSS.
- Fitur UI:
  - Input `name` (text, autofocus, autocomplete="name").
  - Input `email` (type="email", required).
  - Select/Radio `tipe_pengguna` (Pilihan: Mahasiswa, Dosen, Staf).
  - Input `password` (type="password", minlength="8").
  - Input `password_confirmation` (type="password").
  - Feedback error inline untuk tiap field (`@error`).
  - Alert banner info: *"Registrasi mandiri memerlukan persetujuan dari Admin kampus sebelum akun aktif."*
  - Tombol submit dengan status disabled saat loading.
  - Link navigasi menuju halaman login bagi yang sudah punya akun.

- [ ] **Step 1: Buat layout `resources/views/layouts/guest.blade.php`**
- [ ] **Step 2: Buat view `resources/views/auth/register.blade.php` dengan Tailwind CSS**
- [ ] **Step 3: Buat view `resources/views/auth/login.blade.php` yang menampilkan alert status registrasi**
- [ ] **Step 4: Jalankan build asset `npm run build`**
- [ ] **Step 5: Jalankan browser test / endpoint check**

Run: `php artisan test`
Expected: All tests pass (3/3 or more)

- [ ] **Step 6: Commit perubahan Task 4**

```bash
git add resources/views/layouts/guest.blade.php resources/views/auth/register.blade.php resources/views/auth/login.blade.php public/build/
git commit -m "feat(auth): design responsive registration UI with tailwind css, closes #16"
```

---

### Task 5: Verifikasi Akhir & Regresi

- [ ] **Step 1: Jalankan seluruh test suite**
  Run: `php artisan test`
  Expected: All green (100% pass)
- [ ] **Step 2: Uji manual di web browser**
  Jalankan `php artisan serve`, buka `http://localhost:8000/register`, submit formulir pendaftaran akun mahasiswa dummy, cek database bahwa data masuk dengan `status_akun = 'pending'`.
