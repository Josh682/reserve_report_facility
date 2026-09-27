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

test('admin dashboard displays pending users count and quick action shortcuts', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->pengguna()->pending()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    $response->assertOk()
        ->assertSee('Menunggu Verifikasi')
        ->assertSee('Verifikasi Akun')
        ->assertSee('Tambah Akun Langsung')
        ->assertSee('Tambah Fasilitas Langsung')
        ->assertSee(route('admin.users.create'))
        ->assertSee(route('admin.facilities.create'));
});
