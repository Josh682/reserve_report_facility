<?php

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated user is redirected to login when accessing reservation page', function () {
    $response = $this->get('/reservation');

    $response->assertRedirect('/login');
});

test('authenticated user can view reservation page and active facilities', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'status_akun' => 'verified',
    ]);

    $activeFacility = Facility::factory()->create([
        'nama' => 'Lab Multimedia 1',
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($user)->get('/reservation');

    $response->assertOk();
    $response->assertSee('Lab Multimedia 1');
    $response->assertSee('Peminjaman Fasilitas Saya');
});

test('authenticated user can submit a reservation successfully (US 3)', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'status_akun' => 'verified',
    ]);

    $facility = Facility::factory()->create([
        'nama' => 'Ruang Teater 1',
        'status' => 'aktif',
    ]);

    $payload = [
        'facility_id' => $facility->id,
        'tanggal' => now()->addDays(2)->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '11:00',
        'tujuan_penggunaan' => 'Seminar Himpunan Mahasiswa Informatika',
    ];

    $response = $this->actingAs($user)->post('/reservations', $payload);

    $response->assertRedirect(route('reservation'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('reservations', [
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'status' => 'pending',
        'tujuan_penggunaan' => $payload['tujuan_penggunaan'],
    ]);
});

test('reservation submission fails if facility is in maintenance or inactive', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
        'status_akun' => 'verified',
    ]);

    $facility = Facility::factory()->create([
        'nama' => 'Ruang Rusak',
        'status' => 'dalam_perbaikan',
    ]);

    $payload = [
        'facility_id' => $facility->id,
        'tanggal' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '11:00',
        'tujuan_penggunaan' => 'Belajar kelompok mandiri',
    ];

    $response = $this->actingAs($user)->post('/reservations', $payload);

    $response->assertSessionHasErrors('facility_id');
    $this->assertDatabaseMissing('reservations', [
        'facility_id' => $facility->id,
    ]);
});

test('reservation submission fails if there is a conflicting approved reservation (US 4)', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
        'status_akun' => 'verified',
    ]);

    $otherUser = User::factory()->create([
        'role' => 'pengguna',
        'status_akun' => 'verified',
    ]);

    $facility = Facility::factory()->create([
        'nama' => 'Aula Gedung C',
        'status' => 'aktif',
    ]);

    $date = now()->addDays(3)->format('Y-m-d');

    // Existing approved reservation from 09:00 to 11:00
    Reservation::factory()->create([
        'user_id' => $otherUser->id,
        'facility_id' => $facility->id,
        'tanggal' => $date,
        'start_time' => '09:00:00',
        'end_time' => '11:00:00',
        'status' => 'approved',
    ]);

    // Overlapping attempt from 10:00 to 12:00
    $payload = [
        'facility_id' => $facility->id,
        'tanggal' => $date,
        'start_time' => '10:00',
        'end_time' => '12:00',
        'tujuan_penggunaan' => 'Rapat koordinasi organisasi',
    ];

    $response = $this->actingAs($user)->post('/reservations', $payload);

    $response->assertSessionHasErrors('conflict');
    $this->assertDatabaseMissing('reservations', [
        'user_id' => $user->id,
        'facility_id' => $facility->id,
    ]);
});

test('user can cancel their pending reservation', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
        'status_akun' => 'verified',
    ]);

    $facility = Facility::factory()->create(['status' => 'aktif']);

    $reservation = Reservation::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)->patch("/reservations/{$reservation->id}/cancel");

    $response->assertRedirect(route('reservation'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'status' => 'cancelled',
    ]);
});

test('user cannot cancel another users reservation', function () {
    $user1 = User::factory()->pengguna()->verified()->create();
    $user2 = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create();

    $reservation = Reservation::factory()->create([
        'user_id' => $user1->id,
        'facility_id' => $facility->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user2)->patch("/reservations/{$reservation->id}/cancel");

    $response->assertForbidden();
});

test('pengguna dashboard displays active facilities and recent user reservations', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
        'tipe_pengguna' => 'mahasiswa',
        'status_akun' => 'verified',
    ]);

    $facility = Facility::factory()->create([
        'nama' => 'Ruang Diskusi Perpustakaan',
        'status' => 'aktif',
    ]);

    $reservation = Reservation::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'tujuan_penggunaan' => 'Mengerjakan tugas akhir bersama',
        'status' => 'approved',
    ]);

    $response = $this->actingAs($user)->get('/pengguna/dashboard');

    $response->assertOk();
    $response->assertSee('Ruang Diskusi Perpustakaan');
    $response->assertSee('Mengerjakan tugas akhir bersama');
    $response->assertSee('Peminjaman Disetujui');
});
