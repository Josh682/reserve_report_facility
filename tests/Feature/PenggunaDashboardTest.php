<?php

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated user is redirected to login from pengguna dashboard', function () {
    $response = $this->get('/pengguna/dashboard');

    $response->assertRedirect('/login');
});

test('non-pengguna user cannot access pengguna dashboard', function () {
    $petugas = User::factory()->petugas()->verified()->create();

    $response = $this->actingAs($petugas)->get('/pengguna/dashboard');

    $response->assertForbidden();
});

test('pengguna can view dashboard with accurate report stats and quick action button', function () {
    $user = User::factory()->pengguna()->verified()->create(['name' => 'Budi Santoso']);
    $facility = Facility::factory()->create(['nama' => 'Lab Komputer AI', 'status' => 'aktif']);

    // Buat laporan kerusakan milik pengguna
    $report1 = Report::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'status' => 'baru',
        'kategori' => 'kerusakan',
        'deskripsi' => 'Proyektor ruang kuliah berkedip dan padam tiba-tiba.',
    ]);

    $report2 = Report::factory()->inProgress()->create([
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'kategori' => 'kerusakan',
        'deskripsi' => 'Pendingin ruangan AC bocor menetes ke meja komputer.',
    ]);

    $response = $this->actingAs($user)->get('/pengguna/dashboard');

    $response->assertOk();
    $response->assertSee('Budi Santoso');
    $response->assertSee('+ Lapor Kendala');
    $response->assertSee('Laporan Kendala Saya');
    $response->assertSee('Proyektor ruang kuliah berkedip dan padam tiba-tiba.');
    $response->assertSee('Pendingin ruangan AC bocor menetes ke meja komputer.');
});

test('pengguna dashboard only displays reports belonging to the authenticated user', function () {
    $user1 = User::factory()->pengguna()->verified()->create();
    $user2 = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create(['nama' => 'Ruang Teori B102']);

    Report::factory()->create([
        'user_id' => $user1->id,
        'facility_id' => $facility->id,
        'deskripsi' => 'Kendala papan tulis Ruang B102 milik user satu.',
    ]);

    Report::factory()->create([
        'user_id' => $user2->id,
        'facility_id' => $facility->id,
        'deskripsi' => 'Kendala sound system Ruang B102 milik user dua.',
    ]);

    $response = $this->actingAs($user1)->get('/pengguna/dashboard');

    $response->assertOk();
    $response->assertSee('Kendala papan tulis Ruang B102 milik user satu.');
    $response->assertDontSee('Kendala sound system Ruang B102 milik user dua.');
});
