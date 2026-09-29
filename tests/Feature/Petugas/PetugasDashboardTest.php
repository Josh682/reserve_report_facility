<?php

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access petugas dashboard', function () {
    $response = $this->get('/petugas/dashboard');

    $response->assertRedirect('/login');
});

test('regular user cannot access petugas dashboard', function () {
    $user = User::factory()->pengguna()->verified()->create();

    $response = $this->actingAs($user)->get('/petugas/dashboard');

    $response->assertForbidden();
});

test('petugas can view operational dashboard with reservation and report KPI stats (US 8)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);

    Reservation::factory()->count(3)->create([
        'facility_id' => $facility->id,
        'status' => 'pending',
    ]);

    Report::factory()->count(2)->create([
        'facility_id' => $facility->id,
        'status' => 'baru',
    ]);

    $response = $this->actingAs($petugas)->get('/petugas/dashboard');

    $response->assertOk();
    $response->assertSee('Dashboard Operasional');
    $response->assertSee('Laporan Kerusakan Menunggu');
    $response->assertSee('Antrean Menunggu');
});

test('petugas dashboard displays recent pending reports queue section (US 8)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $facility = Facility::factory()->create(['nama' => 'Laboratorium Rekayasa Perangkat Lunak']);
    $user = User::factory()->pengguna()->verified()->create(['name' => 'Ahmad Pelapor']);

    $report = Report::factory()->create([
        'facility_id' => $facility->id,
        'user_id' => $user->id,
        'kategori' => 'kerusakan',
        'deskripsi' => 'Kabel jaringan dan stopkontak meja depan terputus akibat gigitan kucing liar kampus.',
        'status' => 'baru',
    ]);

    $response = $this->actingAs($petugas)->get('/petugas/dashboard');

    $response->assertOk();
    $response->assertSee('Laboratorium Rekayasa Perangkat Lunak');
    $response->assertSee('Ahmad Pelapor');
    $response->assertSee('Kabel jaringan dan stopkontak meja depan terputus akibat gigitan kucing liar kampus.');
    $response->assertSee(route('petugas.reports.index'));
});

test('petugas dashboard displays empty state when no reports are pending', function () {
    $petugas = User::factory()->petugas()->verified()->create();

    $response = $this->actingAs($petugas)->get('/petugas/dashboard');

    $response->assertOk();
    $response->assertSee('Tidak Ada Laporan Kerusakan Menunggu');
});
