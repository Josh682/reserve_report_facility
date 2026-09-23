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

test('user factory has staf state', function () {
    $stafPengguna = User::factory()->pengguna()->staf()->create();

    expect($stafPengguna->role)->toBe('pengguna')
        ->and($stafPengguna->tipe_pengguna)->toBe('staf');
});

test('admin state defaults to verified and null tipe pengguna', function () {
    $admin = User::factory()->admin()->create();

    expect($admin->role)->toBe('admin')
        ->and($admin->status_akun)->toBe('verified')
        ->and($admin->tipe_pengguna)->toBeNull();
});

test('petugas state defaults to verified and null tipe pengguna', function () {
    $petugas = User::factory()->petugas()->create();

    expect($petugas->role)->toBe('petugas')
        ->and($petugas->status_akun)->toBe('verified')
        ->and($petugas->tipe_pengguna)->toBeNull();
});

test('pengguna state defaults to pending and mahasiswa', function () {
    $pengguna = User::factory()->pengguna()->create();

    expect($pengguna->role)->toBe('pengguna')
        ->and($pengguna->status_akun)->toBe('pending')
        ->and($pengguna->tipe_pengguna)->toBe('mahasiswa');
});
