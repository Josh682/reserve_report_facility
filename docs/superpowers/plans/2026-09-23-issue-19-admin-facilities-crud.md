# Issue #19: Admin: CRUD Data Fasilitas (Tambah/Edit/Nonaktifkan) — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun fitur manajemen master data fasilitas kampus oleh Admin (US 16) mencakup pembuatan Model `Facility` & Factory, validasi via `FacilityRequest`, Controller CRUD & status toggle, layout antarmuka Admin dengan Tailwind CSS, serta automated feature tests.

**Architecture:** Menggunakan arsitektur MVC standar Laravel 11. Model `Facility` merepresentasikan tabel basis data `facilities`. Controller `Admin\FacilityController` menangani operasi CRUD yang dilindungi middleware `['auth', 'role:admin']`. Validasi input ditangani oleh `FacilityRequest`. Penghapusan fasilitas memprioritaskan penonaktifan status (`nonaktif`) untuk menjaga integritas relasi reservasi. Tampilan antarmuka dibangun dengan Blade templates dan Tailwind CSS responsif dengan layout sidebar admin.

**Tech Stack:** PHP 8.5, Laravel 11.x, Blade Templates, Tailwind CSS, Pest PHP Testing Framework.

**Spec:** [docs/superpowers/specs/2026-09-23-auth-and-admin-master-data-design.md](file:///C:/Menza/Projek%20PPk%20-%20reserve_report_facility/docs/superpowers/specs/2026-09-23-auth-and-admin-master-data-design.md) (Bagian 3.3), [ASSUMPTION.md Bagian 2.1 & 2.2](file:///C:/Menza/Projek%20PPk%20-%20reserve_report_facility/ASSUMPTION.md#L36-L58), GitHub Issue #19.

## Global Constraints

- **Tabel Basis Data:** `facilities` (`nama`, `tipe`, `lokasi`, `kapasitas`, `deskripsi`, `status`).
- **Pilihan Tipe Fasilitas:** `ruang_kelas`, `aula`, `laboratorium`, `alat`, `lapangan`.
- **Pilihan Status Fasilitas:** `aktif`, `dalam_perbaikan`, `nonaktif` (default: `aktif`).
- **Hak Akses:** Hanya role `admin` yang dapat mengakses modul ini (dilindungi middleware `['auth', 'role:admin']`).
- **Proteksi Hapus:** Fasilitas dengan riwayat reservasi tidak boleh dihapus permanen, melainkan dinonaktifkan (`status = 'nonaktif'`).
- **Pesan Bahasa Indonesia:** Semua feedback validasi dan status menggunakan bahasa Indonesia.
- **Konvensi Kode:** PHP 8.5 typed properties & explicit return types, PSR-12, Pest Feature Tests.

---

### Task 1: Model Facility & Factory

**Files:**
- Create: `app/Models/Facility.php`
- Create: `database/factories/FacilityFactory.php`
- Create: `tests/Feature/Admin/FacilityModelTest.php`

**Interfaces:**
- Consumes: Skema tabel `facilities`
- Produces: Model `Facility` dengan mass-assignment fillable, type casting, factory, dan query scopes.

- [ ] **Step 1: Tulis failing test untuk Model Facility**

```php
// tests/Feature/Admin/FacilityModelTest.php
<?php

use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('facility model can be created with factory and mass assigned', function () {
    $facility = Facility::factory()->create([
        'nama' => 'Laboratorium Rekayasa Perangkat Lunak',
        'tipe' => 'laboratorium',
        'lokasi' => 'Gedung E Lantai 3',
        'kapasitas' => 40,
        'deskripsi' => 'Lab komputer dengan spesifikasi tinggi.',
        'status' => 'aktif',
    ]);

    expect($facility->nama)->toBe('Laboratorium Rekayasa Perangkat Lunak')
        ->and($facility->tipe)->toBe('laboratorium')
        ->and($facility->lokasi)->toBe('Gedung E Lantai 3')
        ->and($facility->kapasitas)->toBe(40)
        ->and($facility->status)->toBe('aktif');
});

test('facility model scopes filter by status and tipe correctly', function () {
    Facility::factory()->create(['tipe' => 'aula', 'status' => 'aktif']);
    Facility::factory()->create(['tipe' => 'ruang_kelas', 'status' => 'dalam_perbaikan']);
    Facility::factory()->create(['tipe' => 'alat', 'status' => 'nonaktif']);

    expect(Facility::aktif()->count())->toBe(1)
        ->and(Facility::where('tipe', 'aula')->count())->toBe(1);
});
```

- [ ] **Step 2: Jalankan test dan pastikan gagal**

Run: `php artisan test --filter=FacilityModelTest`  
Expected: FAIL (Class `App\Models\Facility` not found)

- [ ] **Step 3: Buat `app/Models/Facility.php` dan `database/factories/FacilityFactory.php`**

```php
// app/Models/Facility.php
<?php

namespace App\Models;

use Database\Factories\FacilityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'tipe', 'lokasi', 'kapasitas', 'deskripsi', 'status'])]
class Facility extends Model
{
    /** @use HasFactory<FacilityFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
        ];
    }

    /**
     * Scope query untuk fasilitas yang berstatus aktif.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }
}
```

```php
// database/factories/FacilityFactory.php
<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->words(3, true),
            'tipe' => fake()->randomElement(['ruang_kelas', 'aula', 'laboratorium', 'alat', 'lapangan']),
            'lokasi' => 'Gedung '.fake()->randomElement(['A', 'B', 'C', 'D', 'E']).' Lantai '.fake()->numberBetween(1, 4),
            'kapasitas' => fake()->numberBetween(10, 100),
            'deskripsi' => fake()->paragraph(),
            'status' => 'aktif',
        ];
    }
}
```

- [ ] **Step 4: Jalankan test dan pastikan lolos**

Run: `php artisan test --filter=FacilityModelTest`  
Expected: PASS (2 tests pass)

- [ ] **Step 5: Commit perubahan Task 1**

```bash
git add app/Models/Facility.php database/factories/FacilityFactory.php tests/Feature/Admin/FacilityModelTest.php
git commit -m "feat(facilities): create Facility model and factory with scopes"
```

---

### Task 2: Form Request Validation (`FacilityRequest`)

**Files:**
- Create: `app/Http/Requests/Admin/FacilityRequest.php`
- Modify: `tests/Feature/Admin/FacilityModelTest.php`

**Interfaces:**
- Consumes: HTTP payload input fasilitas (`nama`, `tipe`, `lokasi`, `kapasitas`, `deskripsi`, `status`)
- Produces: Data tervalidasi dengan pesan kesalahan berbahasa Indonesia.

- [ ] **Step 1: Tambahkan tests untuk validasi form fasilitas**

Tambahkan pengujian di `tests/Feature/Admin/FacilityModelTest.php`:
1. Validasi berhasil untuk data lengkap dan valid.
2. Validasi gagal jika `nama`, `tipe`, `lokasi`, atau `status` kosong.
3. Validasi gagal jika `tipe` atau `status` berisi nilai di luar enum yang diizinkan.
4. Validasi gagal jika `kapasitas` bernilai kurang dari 1 atau bukan integer.

- [ ] **Step 2: Jalankan test dan pastikan gagal**

Run: `php artisan test --filter=FacilityModelTest`  
Expected: FAIL (Class `App\Http\Requests\Admin\FacilityRequest` not found)

- [ ] **Step 3: Buat `app/Http/Requests/Admin/FacilityRequest.php`**

```php
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FacilityRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:150'],
            'tipe' => ['required', 'string', Rule::in(['ruang_kelas', 'aula', 'laboratorium', 'alat', 'lapangan'])],
            'lokasi' => ['required', 'string', 'max:255'],
            'kapasitas' => ['nullable', 'integer', 'min:1'],
            'deskripsi' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['aktif', 'dalam_perbaikan', 'nonaktif'])],
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
            'nama.required' => 'Nama fasilitas wajib diisi.',
            'nama.max' => 'Nama fasilitas maksimal 150 karakter.',
            'tipe.required' => 'Pilih jenis/tipe fasilitas.',
            'tipe.in' => 'Jenis/tipe fasilitas tidak valid.',
            'lokasi.required' => 'Lokasi fasilitas wajib diisi.',
            'lokasi.max' => 'Lokasi fasilitas maksimal 255 karakter.',
            'kapasitas.integer' => 'Kapasitas harus berupa angka.',
            'kapasitas.min' => 'Kapasitas minimal 1 orang/unit.',
            'status.required' => 'Status operasional fasilitas wajib dipilih.',
            'status.in' => 'Status fasilitas tidak valid.',
        ];
    }
}
```

- [ ] **Step 4: Jalankan test dan pastikan lolos**

Run: `php artisan test --filter=FacilityModelTest`  
Expected: PASS

- [ ] **Step 5: Commit perubahan Task 2**

```bash
git add app/Http/Requests/Admin/FacilityRequest.php tests/Feature/Admin/FacilityModelTest.php
git commit -m "feat(facilities): create FacilityRequest with strict validation rules and indonesian error messages"
```

---

### Task 3: Admin Facility Controller & Routes (`Admin\FacilityController`)

**Files:**
- Create: `app/Http/Controllers/Admin/FacilityController.php`
- Modify: `routes/web.php`
- Create: `tests/Feature/Admin/FacilityManagementTest.php`

**Interfaces:**
- Consumes: `FacilityRequest`, `Facility` model
- Produces:
  - `GET /admin/facilities`: Index daftar fasilitas (search, filter, pagination).
  - `GET /admin/facilities/create`: Tampilan form tambah fasilitas.
  - `POST /admin/facilities`: Menyimpan fasilitas baru.
  - `GET /admin/facilities/{id}/edit`: Tampilan form edit fasilitas.
  - `PUT /admin/facilities/{id}`: Memperbarui data fasilitas.
  - `PATCH /admin/facilities/{id}/status`: Aksi cepat ubah status fasilitas.
  - `DELETE /admin/facilities/{id}`: Menghapus fasilitas yang belum memiliki reservasi.

- [ ] **Step 1: Tulis feature tests di `tests/Feature/Admin/FacilityManagementTest.php`**

Uji semua aksi:
1. Admin dapat melihat daftar fasilitas (mendukung search dan filter tipe/status).
2. Admin dapat membuka form tambah dan menyimpan fasilitas baru.
3. Form gagal menyimpan jika input tidak valid.
4. Admin dapat membuka form edit dan mengupdate data fasilitas.
5. Admin dapat memperbarui status fasilitas secara cepat via PATCH.
6. Admin dapat menghapus fasilitas jika belum ada relasi reservasi.
7. Pengguna non-admin (pengguna/petugas) mendapat 403 saat mengakses rute fasilitas admin.

- [ ] **Step 2: Jalankan test dan pastikan gagal**

Run: `php artisan test --filter=FacilityManagementTest`  
Expected: FAIL (Controller / routes tidak ditemukan)

- [ ] **Step 3: Buat `app/Http/Controllers/Admin/FacilityController.php`**

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FacilityRequest;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Facility::query();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->query('tipe'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $facilities = $query->latest()->paginate(10)->withQueryString();

        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.facilities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FacilityRequest $request): RedirectResponse
    {
        Facility::create($request->validated());

        return redirect()->route('admin.facilities.index')->with(
            'status',
            'Fasilitas baru berhasil ditambahkan ke dalam sistem.'
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility): View
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FacilityRequest $request, Facility $facility): RedirectResponse
    {
        $facility->update($request->validated());

        return redirect()->route('admin.facilities.index')->with(
            'status',
            'Data fasilitas berhasil diperbarui.'
        );
    }

    /**
     * Quick status toggle.
     */
    public function updateStatus(Request $request, Facility $facility): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:aktif,dalam_perbaikan,nonaktif'],
        ]);

        $facility->update(['status' => $validated['status']]);

        return back()->with('status', "Status fasilitas '{$facility->nama}' berhasil diubah menjadi ".ucfirst(str_replace('_', ' ', $validated['status'])).'.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility): RedirectResponse
    {
        // Periksa apakah fasilitas sudah pernah memiliki catatan reservasi
        $hasReservations = DB::table('reservations')->where('facility_id', $facility->id)->exists();

        if ($hasReservations) {
            return back()->with(
                'status_error',
                'Fasilitas ini sudah memiliki riwayat reservasi dan tidak boleh dihapus secara permanen. Anda dapat mengubah statusnya menjadi "Nonaktif".'
            );
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')->with(
            'status',
            'Fasilitas berhasil dihapus dari sistem.'
        );
    }
}
```

- [ ] **Step 4: Update `routes/web.php`**

Daftarkan resource rute fasilitas di dalam grup `role:admin`:

```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::patch('/facilities/{facility}/status', [App\Http\Controllers\Admin\FacilityController::class, 'updateStatus'])
        ->name('facilities.status');
    Route::resource('facilities', App\Http\Controllers\Admin\FacilityController::class);
});
```

- [ ] **Step 5: Buat placeholder view untuk sementara**

Buat folder `resources/views/admin/facilities` dan view minimal agar controller dapat merender tanpa error:
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/facilities/index.blade.php`
- `resources/views/admin/facilities/create.blade.php`
- `resources/views/admin/facilities/edit.blade.php`

- [ ] **Step 6: Jalankan test dan pastikan lolos**

Run: `php artisan test --filter=FacilityManagementTest`  
Expected: PASS

- [ ] **Step 7: Commit perubahan Task 3**

```bash
git add app/Http/Controllers/Admin/FacilityController.php routes/web.php tests/Feature/Admin/FacilityManagementTest.php
git commit -m "feat(facilities): implement Admin FacilityController with CRUD and status toggle"
```

---

### Task 4: Desain Layout Antarmuka Admin & Views Fasilitas Tailwind CSS

**Files:**
- Create: `resources/views/layouts/admin.blade.php`
- Create: `resources/views/admin/dashboard.blade.php`
- Modify: `resources/views/admin/facilities/index.blade.php`
- Modify: `resources/views/admin/facilities/create.blade.php`
- Modify: `resources/views/admin/facilities/edit.blade.php`

**Interfaces:**
- Layout admin responsif dengan sidebar navigasi (Dashboard, Master Fasilitas, Manajemen Pengguna), tombol logout, dan banner alert (sukses/warning/error).
- Tampilan tabel fasilitas lengkap dengan:
  - Form pencarian teks dan filter tipe/status.
  - Badge warna status: `aktif` (hijau), `dalam_perbaikan` (kuning), `nonaktif` (abu/merah).
  - Dropdown/tombol cepat ubah status operasional.
  - Tombol aksi Tambah, Edit, dan Hapus.
  - Pagination Tailwind.
- Form create dan edit fasilitas dengan validasi inline error (`@error`).

- [ ] **Step 1: Buat `resources/views/layouts/admin.blade.php`**
- [ ] **Step 2: Lengkapi `resources/views/admin/dashboard.blade.php`**
- [ ] **Step 3: Lengkapi `resources/views/admin/facilities/index.blade.php`**
- [ ] **Step 4: Lengkapi `resources/views/admin/facilities/create.blade.php` & `edit.blade.php`**
- [ ] **Step 5: Verifikasi tampilan dan test render**

Run: `php artisan test`  
Expected: PASS

- [ ] **Step 6: Commit perubahan Task 4**

```bash
git add resources/views/layouts/admin.blade.php resources/views/admin/dashboard.blade.php resources/views/admin/facilities/
git commit -m "feat(facilities): design admin dashboard and facilities management UI with tailwind css, closes #19"
```

---

### Task 5: Verifikasi Akhir & Regresi Tahap 3

- [ ] **Step 1: Jalankan seluruh test suite**

Run: `php artisan test`  
Expected: 100% tests PASS (seluruh test auth dan fasilitas lulus tanpa ada regresi).

- [ ] **Step 2: Format kode dengan Pint**

Run: `vendor/bin/pint --dirty --format agent`  
Expected: Format kode bersih dan standar PSR-12.
