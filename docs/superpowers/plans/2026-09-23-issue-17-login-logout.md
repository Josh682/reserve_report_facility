# Issue #17: Implementasi Login & Logout untuk Semua Role — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun fitur login dan logout yang aman untuk semua role (`admin`, `petugas`, `pengguna`) dengan validasi kredensial, proteksi status akun (`pending`, `verified`, `rejected`), pengalihan (*redirect*) pasca-login berbasis role, serta penanganan pembatalan sesi (*logout*).

**Architecture:** Menggunakan arsitektur MVC standar Laravel 11. Controller `LoginController` memvalidasi input via `LoginRequest` dan melakukan autentikasi via `Auth::attempt()`. Setelah autentikasi berhasil, controller memeriksa atribut `status_akun` pada model `User`: hanya akun dengan status `verified` yang diizinkan masuk, sementara akun `pending` atau `rejected` langsung di-logout kembali dengan pesan penolakan yang sesuai. Pasca-login dialihkan ke route berdasarkan role (`admin` -> `/admin/dashboard`, `petugas` -> `/petugas/dashboard`, `pengguna` -> `/`). Logout mematikan sesi, menghapus data autentikasi, dan me-regenerate CSRF token.

**Tech Stack:** PHP 8.5, Laravel 11.x, Blade Templates, Tailwind CSS, Pest PHP Testing Framework.

**Spec:** [docs/superpowers/specs/2026-09-23-auth-and-admin-master-data-design.md](file:///C:/Menza/Projek%20PPk%20-%20reserve_report_facility/docs/superpowers/specs/2026-09-23-auth-and-admin-master-data-design.md), [ASSUMPTION.md Bagian 3.2 & 7](file:///C:/Menza/Projek%20PPk%20-%20reserve_report_facility/ASSUMPTION.md#L74-L85), GitHub Issue #17.

## Global Constraints

- **Tabel Basis Data:** `users` (`email`, `password`, `role`, `status_akun`).
- **Aturan Status Akun:** Hanya akun dengan `status_akun = 'verified'` yang diizinkan login. Akun `pending` atau `rejected` dicegah masuk.
- **Role Pengguna:** `admin`, `petugas`, `pengguna`.
- **Redirect Pasca-Login:**
  - `admin` -> route `admin.dashboard` (`/admin/dashboard`)
  - `petugas` -> route `petugas.dashboard` (`/petugas/dashboard`)
  - `pengguna` -> route `home` / `welcome` (`/`)
- **Pesan Bahasa Indonesia:** Semua feedback validasi dan error menggunakan bahasa Indonesia yang ramah.
- **Konvensi Kode:** PHP 8.5 typed properties & explicit return types, PSR-12, Pest Feature Tests.

---

### Task 1: Form Request Validation (`LoginRequest`)

**Files:**
- Create: `app/Http/Requests/Auth/LoginRequest.php`
- Create: `tests/Feature/Auth/LoginTest.php`

**Interfaces:**
- Consumes: HTTP POST `/login` payload (`email`, `password`, `remember`)
- Produces: Data tervalidasi yang siap digunakan oleh `LoginController`.

- [ ] **Step 1: Tulis failing test untuk validasi form login**

```php
// tests/Feature/Auth/LoginTest.php
<?php

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

test('login validation passes with valid email and password', function () {
    $request = new LoginRequest();
    $rules = $request->rules();

    $validator = Validator::make([
        'email' => 'mahasiswa@kampus.test',
        'password' => 'password123',
    ], $rules);

    expect($validator->passes())->toBeTrue();
});

test('login validation fails when email or password is empty or invalid format', function () {
    $request = new LoginRequest();
    $rules = $request->rules();

    $validator = Validator::make([
        'email' => 'not-an-email',
        'password' => '',
    ], $rules);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue()
        ->and($validator->errors()->has('password'))->toBeTrue();
});
```

- [ ] **Step 2: Jalankan test dan pastikan gagal**

Run: `php artisan test --filter=LoginTest`  
Expected: FAIL (Class `App\Http\Requests\Auth\LoginRequest` not found)

- [ ] **Step 3: Buat `app/Http/Requests/Auth/LoginRequest.php`**

```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ];
    }
}
```

- [ ] **Step 4: Jalankan test dan pastikan lolos**

Run: `php artisan test --filter=LoginTest`  
Expected: PASS (2 tests pass)

- [ ] **Step 5: Commit perubahan Task 1**

```bash
git add app/Http/Requests/Auth/LoginRequest.php tests/Feature/Auth/LoginTest.php
git commit -m "feat(auth): create LoginRequest with validation rules and indonesian error messages"
```

---

### Task 2: Controller & Routing (`LoginController`)

**Files:**
- Create: `app/Http/Controllers/Auth/LoginController.php`
- Modify: `routes/web.php`
- Modify: `tests/Feature/Auth/LoginTest.php`

**Interfaces:**
- Consumes: `LoginRequest` (email, password, remember)
- Produces:
  - `GET /login`: Menampilkan view `auth.login`
  - `POST /login`: Memverifikasi kredensial & `status_akun`, regenerate session, dan redirect sesuai role.
  - `POST /logout`: Logout user, invalidate session, regenerate CSRF token, redirect ke `/login`.

- [ ] **Step 1: Tambahkan failing tests untuk alur login, status akun, dan logout**

Tambahkan pengujian pada `tests/Feature/Auth/LoginTest.php`:
1. Guest dapat melihat halaman login (`GET /login`).
2. Pengguna dengan `status_akun = 'verified'` berhasil login dan diarahkan ke rute sesuai role (`admin` -> `/admin/dashboard`, `petugas` -> `/petugas/dashboard`, `pengguna` -> `/`).
3. Pengguna dengan `status_akun = 'pending'` ditolak login dan kembali dengan pesan status pending.
4. Pengguna dengan `status_akun = 'rejected'` ditolak login dan kembali dengan pesan status rejected.
5. Login gagal jika password salah.
6. Pengguna yang sudah login dapat melakukan logout (`POST /logout`).

- [ ] **Step 2: Jalankan test dan pastikan gagal**

Run: `php artisan test --filter=LoginTest`  
Expected: FAIL (Route `POST /login` not supported / 405 Method Not Allowed)

- [ ] **Step 3: Buat `app/Http/Controllers/Auth/LoginController.php`**

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the application login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
            ]);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek status akun sesuai aturan ASSUMPTION.md
        if ($user->status_akun === 'pending') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with(
                'status_warning',
                'Akun Anda sedang menunggu verifikasi oleh Admin sebelum dapat digunakan.'
            );
        }

        if ($user->status_akun === 'rejected') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with(
                'status_error',
                'Akun Anda telah ditolak oleh Admin. Silakan hubungi bagian administrasi.'
            );
        }

        $request->session()->regenerate();

        // Redirect berbasis role
        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'petugas' => redirect()->intended(route('petugas.dashboard')),
            default => redirect()->intended(route('welcome')),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with(
            'status',
            'Anda telah berhasil keluar dari sistem.'
        );
    }
}
```

- [ ] **Step 4: Update `routes/web.php`**

Daftarkan route login, logout, dan placeholder dashboard:

```php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Placeholder sementara untuk tujuan redirect role di Tahap 1
    Route::get('/admin/dashboard', function () {
        return 'Admin Dashboard Placeholder';
    })->name('admin.dashboard');

    Route::get('/petugas/dashboard', function () {
        return 'Petugas Dashboard Placeholder';
    })->name('petugas.dashboard');
});
```

- [ ] **Step 5: Jalankan test dan pastikan lolos**

Run: `php artisan test --filter=LoginTest`  
Expected: PASS (Semua test login & logout lolos)

- [ ] **Step 6: Commit perubahan Task 2**

```bash
git add app/Http/Controllers/Auth/LoginController.php routes/web.php tests/Feature/Auth/LoginTest.php
git commit -m "feat(auth): implement LoginController with account status validation and logout"
```

---

### Task 3: Pembaruan Tampilan Antarmuka Login (`resources/views/auth/login.blade.php`)

**Files:**
- Modify: `resources/views/auth/login.blade.php`

**Interfaces:**
- Menampilkan pesan status warning (`session('status_warning')`) dan status error (`session('status_error')`).
- Menambahkan input checkbox "Ingat Saya" (*Remember Me*).
- Memastikan form mengarah ke `route('login')` dengan method `POST`.

- [ ] **Step 1: Update `resources/views/auth/login.blade.php`**

Tambahkan penanganan alert warning/error status akun dan checkbox "Ingat Saya" di atas tombol submit.

- [ ] **Step 2: Jalankan asset build dan cek rendering view**

Run: `npm run build`  
Run: `php artisan test`  
Expected: PASS

- [ ] **Step 3: Commit perubahan Task 3**

```bash
git add resources/views/auth/login.blade.php
git commit -m "feat(auth): add status alerts and remember me to login view, closes #17"
```

---

### Task 4: Verifikasi Akhir & Regresi Tahap 1

- [ ] **Step 1: Jalankan seluruh test suite**

Run: `php artisan test`  
Expected: 100% tests PASS tanpa ada regresi pada registrasi pengguna (`UserRegistrationTest`).

- [ ] **Step 2: Jalankan linter Pint**

Run: `vendor/bin/pint --dirty --format agent`  
Expected: Format kode bersih dan sesuai standar Laravel.
