# Issues #20, #21, #22: Admin Manajemen & Verifikasi Akun Pengguna & Petugas — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun fitur manajemen dan verifikasi akun pengguna oleh Admin (US 13, 14, 15), mencakup pendaftaran langsung akun Petugas (Issue #20), pendaftaran langsung akun Pengguna (Issue #21), verifikasi & penolakan pendaftar mandiri (Issue #22), antarmuka Blade dengan tab & form Tailwind CSS, serta automated feature tests lengkap dengan Pest.

**Architecture:** Menggunakan arsitektur MVC standar Laravel 11. Controller `Admin\UserController` menangani pengelolaan daftar pengguna terverifikasi & pending, aksi approve/reject, dan registrasi langsung. Validasi ditangani oleh `StoreUserRequest`. Akun yang didaftarkan langsung oleh Admin otomatis berstatus `verified` sesuai `ASSUMPTION.md`. Route diproteksi oleh middleware `['auth', 'role:admin']`. Antarmuka dibangun dengan Blade & Tailwind CSS, terintegrasi ke sidebar dan dashboard admin.

**Tech Stack:** PHP 8.5, Laravel 11.x, Blade Templates, Tailwind CSS, Pest PHP Testing Framework.

**Spec:** [docs/superpowers/specs/2026-09-23-auth-and-admin-master-data-design.md](file:///C:/Menza/Projek%20PPk%20-%20reserve_report_facility/docs/superpowers/specs/2026-09-23-auth-and-admin-master-data-design.md) (Bagian 3.4 & 4.4), [ASSUMPTION.md Bagian 3.2](file:///C:/Menza/Projek%20PPk%20-%20reserve_report_facility/ASSUMPTION.md#L74-L90), GitHub Issues #20, #21, #22.

## Global Constraints

- **Tabel Basis Data:** `users` (`name`, `email`, `password`, `role`, `tipe_pengguna`, `status_akun`).
- **Pilihan Role:** `admin`, `petugas`, `pengguna` (admin mendaftarkan `petugas` atau `pengguna`).
- **Pilihan Tipe Pengguna:** `mahasiswa`, `dosen`, `staf` (hanya berlaku jika role = `pengguna`, jika role = `petugas` maka `tipe_pengguna` bernilai `null`).
- **Pilihan Status Akun:** `pending`, `verified`, `rejected`. Pendaftaran langsung oleh admin otomatis `verified`.
- **Hak Akses:** Hanya role `admin` yang dapat mengakses modul ini (`['auth', 'role:admin']`).
- **Pesan Bahasa Indonesia:** Semua feedback validasi dan status menggunakan bahasa Indonesia.
- **Konvensi Kode:** PHP 8.5 typed properties & explicit return types, PSR-12, Pest Feature Tests.

---

### Task 1: UserFactory States & User Model Scopes

**Files:**
- Modify: `database/factories/UserFactory.php`
- Modify: `app/Models/User.php`
- Test: `tests/Feature/Admin/UserModelTest.php`

**Interfaces:**
- Consumes: Skema tabel `users`
- Produces: Factory states (`admin`, `petugas`, `pengguna`, `pending`, `verified`, `rejected`, `mahasiswa`, `dosen`, `staf`) dan query scopes pada model `User`.

- [ ] **Step 1: Tulis failing test untuk factory states & scopes model User**

```php
// tests/Feature/Admin/UserModelTest.php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user factory has role and account status states', function () {
    $admin = User::factory()->admin()->verified()->create();
    $petugas = User::factory()->petugas()->verified()->create();
    $pendingPengguna = User::factory()->pengguna()->pending()->mahasiswa()->create();
    $rejectedPengguna = User::factory()->pengguna()->rejected()->dosen()->create();

    expect($admin->role)->toBe('admin')
        ->and($admin->status_akun)->toBe('verified')
        ->and($petugas->role)->toBe('petugas')
        ->and($petugas->status_akun)->toBe('verified')
        ->and($petugas->tipe_pengguna)->toBeNull()
        ->and($pendingPengguna->role)->toBe('pengguna')
        ->and($pendingPengguna->status_akun)->toBe('pending')
        ->and($pendingPengguna->tipe_pengguna)->toBe('mahasiswa')
        ->and($rejectedPengguna->status_akun)->toBe('rejected')
        ->and($rejectedPengguna->tipe_pengguna)->toBe('dosen');
});
```

- [ ] **Step 2: Jalankan test untuk memverifikasi kegagalan**

Run: `php artisan test --filter=UserModelTest --compact`
Expected: FAIL (method admin/petugas/pengguna/pending/etc not found on UserFactory)

- [ ] **Step 3: Tambahkan states pada UserFactory dan scopes pada User Model**

Di `database/factories/UserFactory.php`:
```php
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'tipe_pengguna' => null,
            'status_akun' => 'verified',
        ]);
    }

    public function petugas(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'petugas',
            'tipe_pengguna' => null,
            'status_akun' => 'verified',
        ]);
    }

    public function pengguna(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'pengguna',
            'tipe_pengguna' => 'mahasiswa',
            'status_akun' => 'pending',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_akun' => 'pending',
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_akun' => 'verified',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_akun' => 'rejected',
        ]);
    }

    public function mahasiswa(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_pengguna' => 'mahasiswa',
        ]);
    }

    public function dosen(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_pengguna' => 'dosen',
        ]);
    }

    public function staf(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipe_pengguna' => 'staf',
        ]);
    }
```

- [ ] **Step 4: Jalankan test untuk memverifikasi kelolosan**

Run: `php artisan test --filter=UserModelTest --compact`
Expected: PASS

- [ ] **Step 5: Format dengan Pint dan Commit**

Run: `vendor/bin/pint --dirty --format agent`
Run:
```bash
git add database/factories/UserFactory.php app/Models/User.php tests/Feature/Admin/UserModelTest.php
git commit -m "feat(users): add role and status factory states to UserFactory"
```

---

### Task 2: Form Request StoreUserRequest

**Files:**
- Create: `app/Http/Requests/Admin/StoreUserRequest.php`
- Create: `tests/Feature/Admin/StoreUserRequestTest.php`

**Interfaces:**
- Consumes: HTTP POST data (`name`, `email`, `role`, `tipe_pengguna`, `password`, `password_confirmation`)
- Produces: Validated data dengan custom message bahasa Indonesia.

- [ ] **Step 1: Tulis failing test untuk validasi StoreUserRequest**

```php
// tests/Feature/Admin/StoreUserRequestTest.php
<?php

use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

test('store user request passes with valid petugas data', function () {
    $data = [
        'name' => 'Petugas Fasilitas Baru',
        'email' => 'petugas@kampus.ac.id',
        'role' => 'petugas',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $request = new StoreUserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('store user request passes with valid pengguna mahasiswa data', function () {
    $data = [
        'name' => 'Mahasiswa Baru',
        'email' => 'mhs@kampus.ac.id',
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $request = new StoreUserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('store user request fails when pengguna does not provide tipe_pengguna', function () {
    $data = [
        'name' => 'Mahasiswa Tanpa Tipe',
        'email' => 'mhs@kampus.ac.id',
        'role' => 'pengguna',
        'tipe_pengguna' => null,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $request = new StoreUserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('tipe_pengguna'))->toBeTrue();
});

test('store user request fails when email is already registered', function () {
    User::factory()->create(['email' => 'existing@kampus.ac.id']);

    $data = [
        'name' => 'User Duplikat',
        'email' => 'existing@kampus.ac.id',
        'role' => 'petugas',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $request = new StoreUserRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('email'))->toBeTrue();
});
```

- [ ] **Step 2: Jalankan test untuk memverifikasi kegagalan**

Run: `php artisan test --filter=StoreUserRequestTest --compact`
Expected: FAIL (Class StoreUserRequest not found)

- [ ] **Step 3: Implementasikan StoreUserRequest**

Gunakan command: `php artisan make:request Admin/StoreUserRequest --no-interaction`
Isi `app/Http/Requests/Admin/StoreUserRequest.php`:
```php
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['petugas', 'pengguna'])],
            'tipe_pengguna' => [
                Rule::requiredIf(fn () => $this->input('role') === 'pengguna'),
                'nullable',
                Rule::in(['mahasiswa', 'dosen', 'staf']),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar dalam sistem.',
            'role.required' => 'Role akun wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'tipe_pengguna.required' => 'Tipe pengguna wajib dipilih jika role adalah pengguna.',
            'tipe_pengguna.in' => 'Tipe pengguna yang dipilih tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ];
    }
}
```

- [ ] **Step 4: Jalankan test untuk memverifikasi kelolosan**

Run: `php artisan test --filter=StoreUserRequestTest --compact`
Expected: PASS

- [ ] **Step 5: Format dengan Pint dan Commit**

Run: `vendor/bin/pint --dirty --format agent`
Run:
```bash
git add app/Http/Requests/Admin/StoreUserRequest.php tests/Feature/Admin/StoreUserRequestTest.php
git commit -m "feat(users): create StoreUserRequest with conditional validation rules"
```

---

### Task 3: UserController (Pendaftaran Langsung & Verifikasi Akun)

**Files:**
- Create: `app/Http/Controllers/Admin/UserController.php`
- Modify: `routes/web.php`
- Create: `tests/Feature/Admin/UserManagementTest.php`

**Interfaces:**
- Consumes: `StoreUserRequest`, `User` model
- Produces: Action methods `index`, `create`, `store`, `approve`, `reject` pada `Admin\UserController`.

- [ ] **Step 1: Tulis failing test untuk fungsionalitas UserController**

```php
// tests/Feature/Admin/UserManagementTest.php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view users management page with pending and all tabs', function () {
    $admin = User::factory()->admin()->create();
    $pendingUser = User::factory()->pengguna()->pending()->create(['name' => 'Pending Bob']);
    $activePetugas = User::factory()->petugas()->verified()->create(['name' => 'Petugas Alice']);

    $response = $this->actingAs($admin)->get(route('admin.users.index', ['tab' => 'pending']));
    $response->assertOk()
        ->assertSee('Pending Bob');

    $responseAll = $this->actingAs($admin)->get(route('admin.users.index', ['tab' => 'all']));
    $responseAll->assertOk()
        ->assertSee('Petugas Alice');
});

test('admin can view direct user creation form', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.users.create'));
    $response->assertOk()
        ->assertSee('Tambah Akun Baru');
});

test('admin can directly create a petugas account which is automatically verified (Issue #20)', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Pak Budi Petugas',
        'email' => 'budi.petugas@kampus.ac.id',
        'role' => 'petugas',
        'password' => 'petugas1234',
        'password_confirmation' => 'petugas1234',
    ]);

    $response->assertRedirect(route('admin.users.index', ['tab' => 'all']))
        ->assertSessionHas('status', 'Akun petugas berhasil dibuat dan langsung berstatus aktif.');

    $user = User::where('email', 'budi.petugas@kampus.ac.id')->first();
    expect($user)->not->toBeNull()
        ->and($user->role)->toBe('petugas')
        ->and($user->status_akun)->toBe('verified')
        ->and($user->tipe_pengguna)->toBeNull();
});

test('admin can directly create a pengguna account which is automatically verified (Issue #21)', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Siti Mahasiswi',
        'email' => 'siti@kampus.ac.id',
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('admin.users.index', ['tab' => 'all']))
        ->assertSessionHas('status', 'Akun pengguna berhasil dibuat dan langsung berstatus aktif.');

    $user = User::where('email', 'siti@kampus.ac.id')->first();
    expect($user)->not->toBeNull()
        ->and($user->role)->toBe('pengguna')
        ->and($user->status_akun)->toBe('verified')
        ->and($user->tipe_pengguna)->toBe('mahasiswa');
});

test('admin can approve a pending user account (Issue #22)', function () {
    $admin = User::factory()->admin()->create();
    $pendingUser = User::factory()->pengguna()->pending()->create(['name' => 'Calon Pengguna']);

    $response = $this->actingAs($admin)->patch(route('admin.users.approve', $pendingUser));

    $response->assertRedirect()
        ->assertSessionHas('status', "Akun {$pendingUser->name} berhasil diverifikasi dan disetujui.");

    expect($pendingUser->fresh()->status_akun)->toBe('verified');
});

test('admin can reject a pending user account (Issue #22)', function () {
    $admin = User::factory()->admin()->create();
    $pendingUser = User::factory()->pengguna()->pending()->create(['name' => 'Akun Ditolak']);

    $response = $this->actingAs($admin)->patch(route('admin.users.reject', $pendingUser));

    $response->assertRedirect()
        ->assertSessionHas('status', "Akun {$pendingUser->name} telah ditolak.");

    expect($pendingUser->fresh()->status_akun)->toBe('rejected');
});

test('regular user cannot access admin user management routes', function () {
    $user = User::factory()->pengguna()->verified()->create();

    $response = $this->actingAs($user)->get(route('admin.users.index'));
    $response->assertForbidden();
});
```

- [ ] **Step 2: Jalankan test untuk memverifikasi kegagalan**

Run: `php artisan test --filter=UserManagementTest --compact`
Expected: FAIL (routes or controller not found)

- [ ] **Step 3: Daftarkan routes di `routes/web.php`**

Di grup `Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(...)`:
```php
use App\Http\Controllers\Admin\UserController;

// ...
Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
Route::patch('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
Route::resource('users', UserController::class)->only(['index', 'create', 'store']);
```

- [ ] **Step 4: Buat `App\Http\Controllers\Admin\UserController`**

Buat file `app/Http/Controllers/Admin/UserController.php`:
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request): View
    {
        $currentTab = $request->query('tab', 'pending');
        $search = $request->query('search');
        $roleFilter = $request->query('role');
        $statusFilter = $request->query('status');

        $pendingCount = User::where('status_akun', 'pending')->count();
        $allCount = User::count();

        // Query untuk pending users (Tab 1)
        $pendingUsers = User::where('status_akun', 'pending')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'pending_page');

        // Query untuk semua users (Tab 2)
        $allUsers = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($roleFilter, function ($query, $role) {
                $query->where('role', $role);
            })
            ->when($statusFilter, function ($query, $status) {
                $query->where('status_akun', $status);
            })
            ->latest()
            ->paginate(10, ['*'], 'all_page');

        return view('admin.users.index', compact(
            'pendingUsers',
            'allUsers',
            'currentTab',
            'pendingCount',
            'allCount',
            'search',
            'roleFilter',
            'statusFilter'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $role = $validated['role'];
        $tipePengguna = ($role === 'pengguna') ? ($validated['tipe_pengguna'] ?? 'mahasiswa') : null;

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $role,
            'tipe_pengguna' => $tipePengguna,
            'status_akun' => 'verified',
        ]);

        $message = ($role === 'petugas')
            ? 'Akun petugas berhasil dibuat dan langsung berstatus aktif.'
            : 'Akun pengguna berhasil dibuat dan langsung berstatus aktif.';

        return redirect()->route('admin.users.index', ['tab' => 'all'])
            ->with('status', $message);
    }

    /**
     * Approve a pending user account.
     */
    public function approve(User $user): RedirectResponse
    {
        $user->update(['status_akun' => 'verified']);

        return redirect()->back()
            ->with('status', "Akun {$user->name} berhasil diverifikasi dan disetujui.");
    }

    /**
     * Reject a pending user account.
     */
    public function reject(User $user): RedirectResponse
    {
        $user->update(['status_akun' => 'rejected']);

        return redirect()->back()
            ->with('status', "Akun {$user->name} telah ditolak.");
    }
}
```

- [ ] **Step 5: Buat placeholder Blade views sementara untuk memverifikasi controller & routes**

Pastikan folder `resources/views/admin/users` dibuat, buat `index.blade.php` dan `create.blade.php` minimal.

- [ ] **Step 6: Jalankan test untuk memverifikasi kelolosan logic controller**

Run: `php artisan test --filter=UserManagementTest --compact`
Expected: PASS

- [ ] **Step 7: Format dengan Pint dan Commit**

Run: `vendor/bin/pint --dirty --format agent`
Run:
```bash
git add app/Http/Controllers/Admin/UserController.php routes/web.php tests/Feature/Admin/UserManagementTest.php
git commit -m "feat(users): implement UserController with direct registration and verification approval/rejection"
```

---

### Task 4: Antarmuka Blade: Halaman Manajemen & Pendaftaran Akun

**Files:**
- Create: `resources/views/admin/users/index.blade.php`
- Create: `resources/views/admin/users/create.blade.php`
- Modify: `resources/views/layouts/admin.blade.php`
- Modify: `resources/views/admin/dashboard.blade.php`
- Modify: `routes/web.php` (tambahkan pending count pada admin.dashboard)

**Interfaces:**
- Consumes: Data `pendingUsers`, `allUsers`, `currentTab`, flash messages
- Produces: Responsif Tailwind CSS user management interface, tab switching, pendaftaran user form dengan toggle dinamis tipe pengguna, dan link navigasi sidebar.

- [ ] **Step 1: Desain `resources/views/admin/users/index.blade.php`**

Fitur antarmuka:
- Tab 1: **Verifikasi Akun (Pending)** — dilengkapi badge hitungan pending, tabel pendaftar mandiri dengan tombol aksi cepat *Setujui* (form PATCH `users.approve`) dan *Tolak* (form PATCH `users.reject`).
- Tab 2: **Semua Pengguna Terdaftar** — tabel lengkap seluruh akun (`Admin`, `Petugas`, `Pengguna`), filter dropdown berdasarkan role dan status akun, badge warna status (`verified` hijau, `pending` kuning, `rejected` merah/abu).
- Tombol CTA **+ Tambah Akun Baru** mengarah ke `route('admin.users.create')`.
- Empty state yang rapi jika tidak ada akun dalam antrean pending.

- [ ] **Step 2: Desain `resources/views/admin/users/create.blade.php`**

Fitur antarmuka:
- Form pendaftaran langsung oleh admin.
- Pilihan Role Radio/Dropdown: `Petugas Fasilitas` atau `Pengguna (Civitas Akademika)`.
- Input dinamis: Bila memilih role `Pengguna`, input `Tipe Pengguna` (`Mahasiswa`, `Dosen`, `Staf`) aktif/muncul. Bila memilih `Petugas`, input disembunyikan/dinonaktifkan secara otomatis via JavaScript sederhana.
- Input Nama Lengkap, Email Kampus, Kata Sandi, dan Konfirmasi Kata Sandi.
- Banner informasi: *"Akun yang dibuat langsung oleh Admin otomatis terverifikasi dan dapat segera digunakan untuk login."*
- Penanganan pesan error validasi inline dari `StoreUserRequest`.

- [ ] **Step 3: Update `resources/views/layouts/admin.blade.php` & `admin.dashboard`**

- Tambahkan menu link **Manajemen Pengguna** di sidebar navigasi admin (dengan status active link `request()->routeIs('admin.users.*')`).
- Perbarui route `/admin/dashboard` di `routes/web.php` untuk menghitung `$stats['pending_users'] = User::where('status_akun', 'pending')->count();`.
- Tampilkan kartu metrik "Pendaftar Menunggu Verifikasi" dan aksi cepat ke halaman verifikasi pengguna di `resources/views/admin/dashboard.blade.php`.

- [ ] **Step 4: Jalankan seluruh test suite Pest**

Run: `php artisan test --compact`
Expected: Seluruh test (42 existing + test baru) PASS 100%.

- [ ] **Step 5: Format dengan Pint dan Commit**

Run: `vendor/bin/pint --dirty --format agent`
Run:
```bash
git add resources/views/admin/users/ resources/views/layouts/admin.blade.php resources/views/admin/dashboard.blade.php routes/web.php
git commit -m "feat(users): design user management tabs and direct account creation UI with tailwind css, closes #20, closes #21, closes #22"
```

---

### Task 5: Verifikasi Akhir & Regresi

**Files:**
- Test all: `tests/Feature/`

- [ ] **Step 1: Jalankan validasi route list**

Run: `php artisan route:list --path=admin`
Expected: Seluruh route admin (`facilities.*`, `users.*`, `dashboard`) terdaftar dan terikat pada controller yang benar.

- [ ] **Step 2: Jalankan full suite Pest tests**

Run: `php artisan test --compact`
Expected: All tests pass.

- [ ] **Step 3: Periksa git diff & status**

Run: `git status`
Run: `vendor/bin/pint --format agent`
