<?php

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated user is redirected to login when accessing petugas reservations', function () {
    $response = $this->get('/petugas/reservations');

    $response->assertRedirect('/login');
});

test('regular pengguna is forbidden from accessing petugas reservations', function () {
    $pengguna = User::factory()->pengguna()->verified()->create();

    $response = $this->actingAs($pengguna)->get('/petugas/reservations');

    $response->assertForbidden();
});

test('petugas can view reservations queue and see pending reservations', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $facility = Facility::factory()->create(['nama' => 'Aula Gedung B']);
    $user = User::factory()->pengguna()->verified()->create(['name' => 'Budi Santoso']);

    $reservation = Reservation::factory()->create([
        'facility_id' => $facility->id,
        'user_id' => $user->id,
        'status' => 'pending',
        'tujuan_penggunaan' => 'Seminar Kecerdasan Buatan',
    ]);

    $response = $this->actingAs($petugas)->get('/petugas/reservations');

    $response->assertOk();
    $response->assertSee('Aula Gedung B');
    $response->assertSee('Budi Santoso');
    $response->assertSee('Seminar Kecerdasan Buatan');
    $response->assertSee('Setujui (Approve)');
});

test('petugas can approve a pending reservation (US 9)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);

    $reservation = Reservation::factory()->create([
        'facility_id' => $facility->id,
        'status' => 'pending',
        'tanggal' => now()->addDays(3)->toDateString(),
        'start_time' => '09:00',
        'end_time' => '11:00',
    ]);

    $response = $this->actingAs($petugas)->patch("/petugas/reservations/{$reservation->id}/approve");

    $response->assertRedirect();
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'status' => 'approved',
        'processed_by' => $petugas->id,
    ]);
});

test('approving reservation automatically rejects overlapping pending reservations (US 9)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);
    $date = now()->addDays(4)->toDateString();

    // Permohonan A: 09:00 - 11:00
    $resA = Reservation::factory()->create([
        'facility_id' => $facility->id,
        'status' => 'pending',
        'tanggal' => $date,
        'start_time' => '09:00',
        'end_time' => '11:00',
    ]);

    // Permohonan B (tumpang tindih waktu): 10:00 - 12:00
    $resB = Reservation::factory()->create([
        'facility_id' => $facility->id,
        'status' => 'pending',
        'tanggal' => $date,
        'start_time' => '10:00',
        'end_time' => '12:00',
    ]);

    // Permohonan C (tidak tumpang tindih waktu): 13:00 - 15:00
    $resC = Reservation::factory()->create([
        'facility_id' => $facility->id,
        'status' => 'pending',
        'tanggal' => $date,
        'start_time' => '13:00',
        'end_time' => '15:00',
    ]);

    // Petugas menyetujui Permohonan A
    $response = $this->actingAs($petugas)->patch("/petugas/reservations/{$resA->id}/approve");

    $response->assertRedirect();
    $response->assertSessionHas('status');

    // Res A disetujui
    $this->assertDatabaseHas('reservations', [
        'id' => $resA->id,
        'status' => 'approved',
    ]);

    // Res B otomatis ditolak karena bentrok
    $this->assertDatabaseHas('reservations', [
        'id' => $resB->id,
        'status' => 'rejected',
        'processed_by' => $petugas->id,
    ]);

    // Res C tetap pending (karena tidak bentrok)
    $this->assertDatabaseHas('reservations', [
        'id' => $resC->id,
        'status' => 'pending',
    ]);
});

test('petugas cannot approve reservation if another approved reservation already conflicts', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);
    $date = now()->addDays(2)->toDateString();

    // Existing approved reservation
    Reservation::factory()->create([
        'facility_id' => $facility->id,
        'status' => 'approved',
        'tanggal' => $date,
        'start_time' => '08:00',
        'end_time' => '10:00',
    ]);

    // Pending reservation overlapping
    $pending = Reservation::factory()->create([
        'facility_id' => $facility->id,
        'status' => 'pending',
        'tanggal' => $date,
        'start_time' => '09:00',
        'end_time' => '11:00',
    ]);

    $response = $this->actingAs($petugas)->patch("/petugas/reservations/{$pending->id}/approve");

    $response->assertSessionHas('status_error');

    $this->assertDatabaseHas('reservations', [
        'id' => $pending->id,
        'status' => 'pending',
    ]);
});

test('petugas can reject a pending reservation with custom reason (US 9)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $reservation = Reservation::factory()->create([
        'status' => 'pending',
    ]);

    $response = $this->actingAs($petugas)->patch("/petugas/reservations/{$reservation->id}/reject", [
        'alasan' => 'Ruangan telah dipesan untuk Dies Natalis oleh Rektorat.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'status' => 'rejected',
        'processed_by' => $petugas->id,
        'cancelled_reason' => 'Ruangan telah dipesan untuk Dies Natalis oleh Rektorat.',
    ]);
});

test('petugas can perform emergency cancellation on approved reservation with reason (US 10)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $reservation = Reservation::factory()->create([
        'status' => 'approved',
    ]);

    $reason = 'Terjadi korsleting listrik di ruangan lab sehingga pendingin ruangan mati total.';

    $response = $this->actingAs($petugas)->patch("/petugas/reservations/{$reservation->id}/emergency-cancel", [
        'cancelled_reason' => $reason,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'status' => 'cancelled',
        'processed_by' => $petugas->id,
    ]);

    $this->assertStringContainsString($reason, $reservation->fresh()->cancelled_reason);
});

test('emergency cancellation fails if reason is less than 10 characters (US 10)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $reservation = Reservation::factory()->create([
        'status' => 'approved',
    ]);

    $response = $this->actingAs($petugas)->patch("/petugas/reservations/{$reservation->id}/emergency-cancel", [
        'cancelled_reason' => 'rusak', // Too short (5 chars)
    ]);

    $response->assertSessionHasErrors('cancelled_reason');

    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'status' => 'approved',
    ]);
});

test('emergency cancel cannot be performed on pending or rejected reservation', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $reservation = Reservation::factory()->create([
        'status' => 'pending',
    ]);

    $response = $this->actingAs($petugas)->patch("/petugas/reservations/{$reservation->id}/emergency-cancel", [
        'cancelled_reason' => 'Alasan pembatalan mendadak yang panjang minimal sepuluh karakter.',
    ]);

    $response->assertSessionHas('status_error');

    $this->assertDatabaseHas('reservations', [
        'id' => $reservation->id,
        'status' => 'pending',
    ]);
});
