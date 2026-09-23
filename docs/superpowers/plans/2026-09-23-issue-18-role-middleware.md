# Issue #18: Implementasi Middleware Pembatasan Akses per Role — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun middleware otorisasi berbasis role (`EnsureUserRole`) dengan alias `'role'` untuk membatasi akses route berdasarkan role pengguna (`admin`, `petugas`, `pengguna`), mengembalikan respon 403 jika hak akses tidak sesuai, serta melindungi grup rute admin dan petugas.

**Architecture:** Menggunakan mekanisme middleware Laravel 11. Middleware `EnsureUserRole` menerima argumen role (variadic `string ...$roles`), memeriksa atribut `role` pada model `User` yang terautentikasi, dan memblokir akses yang tidak sah menggunakan `abort(403)`. Alias `'role'` didaftarkan pada `bootstrap/app.php`. Rute `/admin/*` dan `/petugas/*` di `routes/web.php` dilindungi dengan kombinasi middleware `['auth', 'role:admin']` dan `['auth', 'role:petugas']`.

**Tech Stack:** PHP 8.5, Laravel 11.x, Pest PHP Testing Framework.

**Spec:** [docs/superpowers/specs/2026-09-23-auth-and-admin-master-data-design.md](file:///C:/Menza/Projek%20PPk%20-%20reserve_report_facility/docs/superpowers/specs/2026-09-23-auth-and-admin-master-data-design.md) (Bagian 3.2), GitHub Issue #18.

## Global Constraints

- **Role yang Didukung:** `admin`, `petugas`, `pengguna`.
- **Alias Middleware:** `'role'` (didaftarkan via `$middleware->alias(['role' => ...])` di `bootstrap/app.php`).
- **Respon Penolakan:** HTTP 403 (*Forbidden*) dengan pesan bahasa Indonesia: *"Anda tidak memiliki hak akses untuk membuka halaman ini."*
- **Penanganan Guest:** Jika pengguna belum login, arahkan ke rute `login`.
- **Konvensi Kode:** PHP 8.5 typed properties & return types, PSR-12, Pest Feature Tests.

---

### Task 1: Middleware Implementation (`EnsureUserRole`) & Alias Registration

**Files:**
- Create: `app/Http/Middleware/EnsureUserRole.php`
- Modify: `bootstrap/app.php`
- Create: `tests/Feature/Auth/RoleMiddlewareTest.php`

**Interfaces:**
- Consumes: `Illuminate\Http\Request`, `Closure $next`, `string ...$roles`
- Produces: Middleware yang meloloskan request jika role sesuai, atau melempar 403 jika tidak berhak.

- [ ] **Step 1: Tulis failing feature tests untuk role middleware**

```php
// tests/Feature/Auth/RoleMiddlewareTest.php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    Route::middleware(['web', 'auth', 'role:admin'])->get('/test-admin-only', function () {
        return 'Admin Access Granted';
    });

    Route::middleware(['web', 'auth', 'role:petugas'])->get('/test-petugas-only', function () {
        return 'Petugas Access Granted';
    });

    Route::middleware(['web', 'auth', 'role:admin,petugas'])->get('/test-multi-role', function () {
        return 'Multi Role Access Granted';
    });
});

test('admin can access route protected by role:admin', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'status_akun' => 'verified',
    ]);

    $response = $this->actingAs($admin)->get('/test-admin-only');

    $response->assertStatus(200);
    $response->assertSee('Admin Access Granted');
});

test('petugas can access route protected by role:petugas', function () {
    $petugas = User::factory()->create([
        'role' => 'petugas',
        'status_akun' => 'verified',
    ]);

    $response = $this->actingAs($petugas)->get('/test-petugas-only');

    $response->assertStatus(200);
    $response->assertSee('Petugas Access Granted');
});

test('both admin and petugas can access route with role:admin,petugas', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);
    $petugas = User::factory()->create(['role' => 'petugas', 'status_akun' => 'verified']);

    $this->actingAs($admin)->get('/test-multi-role')->assertStatus(200);
    $this->actingAs($petugas)->get('/test-multi-role')->assertStatus(200);
});

test('regular pengguna is forbidden with 403 when accessing role:admin route', function () {
    $pengguna = User::factory()->create([
        'role' => 'pengguna',
        'status_akun' => 'verified',
    ]);

    $response = $this->actingAs($pengguna)->get('/test-admin-only');

    $response->assertStatus(403);
});

test('regular pengguna is forbidden with 403 when accessing role:admin,petugas route', function () {
    $pengguna = User::factory()->create([
        'role' => 'pengguna',
        'status_akun' => 'verified',
    ]);

    $response = $this->actingAs($pengguna)->get('/test-multi-role');

    $response->assertStatus(403);
});

test('guest is redirected to login when accessing protected role route', function () {
    $response = $this->get('/test-admin-only');

    $response->assertRedirect(route('login'));
});
```

- [ ] **Step 2: Jalankan test dan pastikan gagal**

Run: `php artisan test --filter=RoleMiddlewareTest`  
Expected: FAIL (Middleware `role` not found / target class does not exist)

- [ ] **Step 3: Buat `app/Http/Middleware/EnsureUserRole.php`**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
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
}
```

- [ ] **Step 4: Daftarkan alias `'role'` pada `bootstrap/app.php`**

```php
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserRole::class,
        ]);
    })
```

- [ ] **Step 5: Jalankan test dan pastikan lolos**

Run: `php artisan test --filter=RoleMiddlewareTest`  
Expected: PASS (Semua 6 test lulus)

- [ ] **Step 6: Commit perubahan Task 1**

```bash
git add app/Http/Middleware/EnsureUserRole.php bootstrap/app.php tests/Feature/Auth/RoleMiddlewareTest.php
git commit -m "feat(auth): create EnsureUserRole middleware and register role alias"
```

---

### Task 2: Apply Role Middleware to Dashboard / Admin / Petugas Routes

**Files:**
- Modify: `routes/web.php`
- Modify: `tests/Feature/Auth/RoleMiddlewareTest.php`

**Interfaces:**
- Melindungi rute `/admin/dashboard` dengan `['auth', 'role:admin']`.
- Melindungi rute `/petugas/dashboard` dengan `['auth', 'role:petugas']`.

- [ ] **Step 1: Tambahkan tests untuk memverifikasi proteksi route sesungguhnya di `web.php`**

Tambahkan pengujian:
```php
test('admin dashboard route is protected and only accessible by admin', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);
    $petugas = User::factory()->create(['role' => 'petugas', 'status_akun' => 'verified']);
    $pengguna = User::factory()->create(['role' => 'pengguna', 'status_akun' => 'verified']);

    $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);
    $this->actingAs($petugas)->get('/admin/dashboard')->assertStatus(403);
    $this->actingAs($pengguna)->get('/admin/dashboard')->assertStatus(403);
});

test('petugas dashboard route is protected and only accessible by petugas', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);
    $petugas = User::factory()->create(['role' => 'petugas', 'status_akun' => 'verified']);
    $pengguna = User::factory()->create(['role' => 'pengguna', 'status_akun' => 'verified']);

    $this->actingAs($petugas)->get('/petugas/dashboard')->assertStatus(200);
    $this->actingAs($admin)->get('/petugas/dashboard')->assertStatus(403);
    $this->actingAs($pengguna)->get('/petugas/dashboard')->assertStatus(403);
});
```

- [ ] **Step 2: Update `routes/web.php`**

Terapkan middleware `role:admin` dan `role:petugas`:

```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return 'Admin Dashboard Placeholder';
    })->name('dashboard');
});

Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', function () {
        return 'Petugas Dashboard Placeholder';
    })->name('dashboard');
});
```

- [ ] **Step 3: Jalankan test dan pastikan lolos**

Run: `php artisan test --filter=RoleMiddlewareTest`  
Expected: PASS

- [ ] **Step 4: Commit perubahan Task 2**

```bash
git add routes/web.php tests/Feature/Auth/RoleMiddlewareTest.php
git commit -m "feat(auth): apply role middleware to admin and petugas routes, closes #18"
```

---

### Task 3: Verifikasi Akhir Seluruh Test Suite & Code Formatting

- [ ] **Step 1: Jalankan seluruh test suite**

Run: `php artisan test`  
Expected: 100% tests PASS (28 tests lulus tanpa ada error atau regresi).

- [ ] **Step 2: Format kode dengan Pint**

Run: `vendor/bin/pint --dirty --format agent`  
Expected: Kode rapi dan standar PSR-12.
