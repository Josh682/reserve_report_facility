<?php

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

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
        'tanggal' => now()->addDays(2)->toDateString(),
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

test('user can cancel their approved reservation if done in advance (H-1) (US 4 & ASSUMPTION.md 1.2)', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
        'status_akun' => 'verified',
    ]);

    $facility = Facility::factory()->create(['status' => 'aktif']);

    $reservation = Reservation::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'tanggal' => now()->addDays(3)->toDateString(),
        'status' => 'approved',
    ]);

    $response = $this->actingAs($user)->patch("/reservations/{$reservation->id}/cancel");

    $response->assertRedirect(route('reservation'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'status' => 'cancelled',
    ]);
});

test('user cannot cancel reservation on the day of use (violates H-1 boundary) (US 4 & ASSUMPTION.md 1.2)', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
        'status_akun' => 'verified',
    ]);

    $facility = Facility::factory()->create(['status' => 'aktif']);

    // Reservation is today (hari-H)
    $reservation = Reservation::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'tanggal' => now()->toDateString(),
        'status' => 'pending',
    ]);

    $response = $this->actingAs($user)->patch("/reservations/{$reservation->id}/cancel");

    $response->assertSessionHas('status_error');

    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'status' => 'pending',
    ]);
});

test('user cannot submit reservation with non-30-minute interval', function () {
    $user = User::factory()->create([
        'role' => 'pengguna',
        'status_akun' => 'verified',
    ]);

    $facility = Facility::factory()->create(['status' => 'aktif']);

    $payload = [
        'facility_id' => $facility->id,
        'tanggal' => now()->addDays(2)->toDateString(),
        'start_time' => '09:15', // Invalid: not :00 or :30
        'end_time' => '10:45',   // Invalid: not :00 or :30
        'tujuan_penggunaan' => 'Praktikum tidak valid interval',
    ];

    $response = $this->actingAs($user)->post('/reservations', $payload);

    $response->assertSessionHasErrors(['start_time', 'end_time']);
});

test('user cannot cancel another users reservation', function () {
    $user1 = User::factory()->pengguna()->verified()->create();
    $user2 = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create();

    $reservation = Reservation::factory()->create([
        'user_id' => $user1->id,
        'facility_id' => $facility->id,
        'tanggal' => now()->addDays(2)->toDateString(),
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

test('reservation rejects invalid booking dates and times', function (string $clock, string $date, mixed $start, mixed $end, string $field) {
    $this->travelTo(Carbon::parse($clock, 'Asia/Jakarta'));
    $user = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);

    $response = $this->actingAs($user)->post('/reservations', [
        'facility_id' => $facility->id,
        'tanggal' => $date,
        'start_time' => $start,
        'end_time' => $end,
        'tujuan_penggunaan' => 'Rapat koordinasi mahasiswa',
    ]);

    $response->assertSessionHasErrors($field);
    $this->assertDatabaseCount('reservations', 0);
})->with([
    'past time today in WIB' => ['2026-09-30 10:15:00', '2026-09-30', '10:00', '11:00', 'start_time'],
    'elapsed seconds in current minute' => ['2026-09-30 10:00:01', '2026-09-30', '10:00', '11:00', 'start_time'],
    'past date across UTC midnight' => ['2026-09-30 00:15:00', '2026-09-29', '19:00', '20:00', 'tanggal'],
    '61 days ahead' => ['2026-09-30 10:15:00', '2026-11-30', '09:00', '10:00', 'tanggal'],
    'two years ahead' => ['2026-09-30 10:15:00', '2028-09-30', '09:00', '10:00', 'tanggal'],
    'six and a half hours' => ['2026-09-30 10:15:00', '2026-10-01', '07:00', '13:30', 'end_time'],
    'thirteen hours' => ['2026-09-30 10:15:00', '2026-10-01', '07:00', '20:00', 'end_time'],
    'invalid date' => ['2026-09-30 10:15:00', 'not-a-date', '09:00', '10:00', 'tanggal'],
    'invalid start' => ['2026-09-30 10:15:00', '2026-10-01', 'invalid', '10:00', 'start_time'],
    'array start' => ['2026-09-30 10:15:00', '2026-10-01', ['09:00'], '10:00', 'start_time'],
    'invalid end' => ['2026-09-30 10:15:00', '2026-10-01', '09:00', 'invalid', 'end_time'],
    'equal start and end' => ['2026-09-30 10:15:00', '2026-10-01', '10:00', '10:00', 'end_time'],
    'array end' => ['2026-09-30 10:15:00', '2026-10-01', '09:00', ['10:00'], 'end_time'],
    'end before start' => ['2026-09-30 10:15:00', '2026-10-01', '10:00', '09:00', 'end_time'],
    'before opening' => ['2026-09-30 10:15:00', '2026-10-01', '06:30', '07:30', 'start_time'],
    'after closing' => ['2026-09-30 10:15:00', '2026-10-01', '19:00', '20:30', 'end_time'],
]);

test('reservation accepts valid booking boundaries', function (string $clock, string $date, string $start, string $end) {
    $this->travelTo(Carbon::parse($clock, 'Asia/Jakarta'));
    $user = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);

    $response = $this->actingAs($user)->post('/reservations', [
        'facility_id' => $facility->id,
        'tanggal' => $date,
        'start_time' => $start,
        'end_time' => $end,
        'tujuan_penggunaan' => 'Rapat koordinasi mahasiswa',
    ]);

    $response->assertSessionHasNoErrors()->assertRedirect(route('reservation'));
    $this->assertDatabaseHas('reservations', [
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'status' => 'pending',
    ]);
})->with([
    'next slot today' => ['2026-09-30 10:15:00', '2026-09-30', '10:30', '11:00'],
    'exact current instant' => ['2026-09-30 10:00:00', '2026-09-30', '10:00', '11:00'],
    'earlier clock time tomorrow' => ['2026-09-30 10:15:00', '2026-10-01', '07:00', '08:00'],
    'exactly sixty days and six hours' => ['2026-09-30 10:15:00', '2026-11-29', '14:00', '20:00'],
    'today before UTC date changes' => ['2026-09-30 00:15:00', '2026-09-30', '07:00', '08:00'],
]);

test('reservation form uses WIB dates and shows booking limits', function () {
    $this->travelTo(Carbon::parse('2026-09-30 00:15:00', 'Asia/Jakarta'));
    $user = User::factory()->pengguna()->verified()->create();

    $this->actingAs($user)->get('/reservation')
        ->assertOk()
        ->assertSee('min="2026-09-30"', false)
        ->assertSee('max="2026-11-29"', false)
        ->assertSee('Reservasi hingga 60 hari ke depan, maksimal 6 jam per peminjaman.');
});
