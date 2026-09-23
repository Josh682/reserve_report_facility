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

test('petugas dashboard renders view with statistics and logout button', function () {
    $petugas = User::factory()->create([
        'name' => 'Petugas Lapangan',
        'role' => 'petugas',
        'status_akun' => 'verified',
    ]);

    $response = $this->actingAs($petugas)->get('/petugas/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Petugas Lapangan');
    $response->assertSee('Dashboard Petugas');
    $response->assertSee('Keluar');
    $response->assertSee(route('logout'));
});

test('pengguna dashboard route is protected and only accessible by pengguna', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);
    $petugas = User::factory()->create(['role' => 'petugas', 'status_akun' => 'verified']);
    $pengguna = User::factory()->create(['role' => 'pengguna', 'status_akun' => 'verified']);

    $this->actingAs($pengguna)->get('/pengguna/dashboard')->assertStatus(200);
    $this->actingAs($admin)->get('/pengguna/dashboard')->assertStatus(403);
    $this->actingAs($petugas)->get('/pengguna/dashboard')->assertStatus(403);
});

test('pengguna dashboard renders view with statistics and single logout button', function () {
    $pengguna = User::factory()->create([
        'name' => 'Mahasiswa Teladan',
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'status_akun' => 'verified',
    ]);

    $response = $this->actingAs($pengguna)->get('/pengguna/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Mahasiswa Teladan');
    $response->assertSee('Dashboard Saya');
    $response->assertSee('Keluar');
    $response->assertSee(route('logout'));
});
