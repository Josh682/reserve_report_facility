<?php

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access petugas reports queue', function () {
    $response = $this->get('/petugas/reports');

    $response->assertRedirect('/login');
});

test('regular user cannot access petugas reports queue', function () {
    $user = User::factory()->pengguna()->verified()->create();

    $response = $this->actingAs($user)->get('/petugas/reports');

    $response->assertForbidden();
});

test('petugas can view reports queue with counters and filters (US 8)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $facility = Facility::factory()->create(['nama' => 'Auditorium Utama']);
    $user = User::factory()->pengguna()->verified()->create(['name' => 'Budi Santoso']);

    $report = Report::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'kategori' => 'kerusakan',
        'deskripsi' => 'Sound system auditorium berdengung keras.',
        'status' => 'baru',
    ]);

    $response = $this->actingAs($petugas)->get('/petugas/reports');

    $response->assertOk();
    $response->assertSee('Auditorium Utama');
    $response->assertSee('Budi Santoso');
    $response->assertSee('Sound system auditorium berdengung keras.');
    $response->assertSee('Laporan Baru');
});

test('petugas can filter reports by status', function () {
    $petugas = User::factory()->petugas()->verified()->create();

    $reportBaru = Report::factory()->create(['status' => 'baru', 'deskripsi' => 'Masalah baru']);
    $reportSelesai = Report::factory()->resolved()->create(['deskripsi' => 'Masalah selesai']);

    $response = $this->actingAs($petugas)->get('/petugas/reports?status=selesai');

    $response->assertOk();
    $response->assertSee('Masalah selesai');
    $response->assertDontSee('Masalah baru');
});

test('petugas can update report status to diproses', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $report = Report::factory()->create(['status' => 'baru']);

    $response = $this->actingAs($petugas)->patch("/petugas/reports/{$report->id}", [
        'status' => 'diproses',
        'catatan_resolusi' => 'Petugas sedang menuju lokasi untuk perbaikan.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('reports', [
        'id' => $report->id,
        'status' => 'diproses',
        'catatan_resolusi' => 'Petugas sedang menuju lokasi untuk perbaikan.',
        'resolved_by' => $petugas->id,
    ]);
});

test('updating report to selesai or ditolak requires catatan_resolusi (US 11)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $report = Report::factory()->create(['status' => 'diproses']);

    // Attempt selesai without catatan_resolusi
    $responseSelesai = $this->actingAs($petugas)->patch("/petugas/reports/{$report->id}", [
        'status' => 'selesai',
        'catatan_resolusi' => '',
    ]);

    $responseSelesai->assertSessionHasErrors(['catatan_resolusi']);

    // Attempt ditolak without catatan_resolusi
    $responseDitolak = $this->actingAs($petugas)->patch("/petugas/reports/{$report->id}", [
        'status' => 'ditolak',
        'catatan_resolusi' => null,
    ]);

    $responseDitolak->assertSessionHasErrors(['catatan_resolusi']);
});

test('petugas can resolve report with resolution note (US 11)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $report = Report::factory()->inProgress()->create();

    $response = $this->actingAs($petugas)->patch("/petugas/reports/{$report->id}", [
        'status' => 'selesai',
        'catatan_resolusi' => 'Kabel audio telah diganti dengan yang baru dan diuji coba lancar.',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('reports', [
        'id' => $report->id,
        'status' => 'selesai',
        'catatan_resolusi' => 'Kabel audio telah diganti dengan yang baru dan diuji coba lancar.',
        'resolved_by' => $petugas->id,
    ]);
});

test('petugas can mark facility as dalam_perbaikan during report update and block reservations (US 12)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);
    $report = Report::factory()->create([
        'facility_id' => $facility->id,
        'status' => 'baru',
    ]);

    $response = $this->actingAs($petugas)->patch("/petugas/reports/{$report->id}", [
        'status' => 'diproses',
        'catatan_resolusi' => 'Fasilitas memerlukan perbaikan intensif selama 2 hari.',
        'mark_facility_status' => 'dalam_perbaikan',
    ]);

    $response->assertRedirect();

    // Pastikan status fasilitas telah berubah di database
    expect($facility->fresh()->status)->toBe('dalam_perbaikan');

    // Pastikan seluruh 26 slot ketersediaan publik terkunci menjadi tidak tersedia
    $schedule = $facility->fresh()->getScheduleForDate(now()->addDays(2)->toDateString());
    expect(collect($schedule)->every(fn ($slot) => ! $slot['is_available']))->toBeTrue();

    // Pastikan reservasi untuk fasilitas ini sekarang otomatis ditolak
    $user = User::factory()->pengguna()->verified()->create();
    $tomorrow = now()->addDays(2)->toDateString();

    $reservationResponse = $this->actingAs($user)->post('/reservations', [
        'facility_id' => $facility->id,
        'tanggal' => $tomorrow,
        'start_time' => '09:00',
        'end_time' => '11:00',
        'tujuan_penggunaan' => 'Rapat kerja himpunan mahasiswa',
    ]);

    $reservationResponse->assertSessionHasErrors(['facility_id']);
    $this->assertDatabaseEmpty('reservations');
});

test('petugas can restore facility status to aktif when report is resolved (US 12)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'dalam_perbaikan']);
    $report = Report::factory()->inProgress()->create([
        'facility_id' => $facility->id,
    ]);

    $response = $this->actingAs($petugas)->patch("/petugas/reports/{$report->id}", [
        'status' => 'selesai',
        'catatan_resolusi' => 'Perbaikan tuntas selesai, fasilitas siap dipakai kembali.',
        'mark_facility_status' => 'aktif',
    ]);

    $response->assertRedirect();

    // Fasilitas aktif kembali
    expect($facility->fresh()->status)->toBe('aktif');

    // Pastikan seluruh 26 slot ketersediaan publik kembali terbuka menjadi tersedia
    $scheduleActive = $facility->fresh()->getScheduleForDate(now()->addDays(2)->toDateString());
    expect(collect($scheduleActive)->every(fn ($slot) => $slot['is_available']))->toBeTrue();

    // Sekarang user bisa memesan fasilitas tersebut
    $user = User::factory()->pengguna()->verified()->create();
    $tomorrow = now()->addDays(2)->toDateString();

    $reservationResponse = $this->actingAs($user)->post('/reservations', [
        'facility_id' => $facility->id,
        'tanggal' => $tomorrow,
        'start_time' => '09:00',
        'end_time' => '11:00',
        'tujuan_penggunaan' => 'Rapat kerja himpunan mahasiswa',
    ]);

    $reservationResponse->assertRedirect(route('reservation'));
    $this->assertDatabaseHas('reservations', [
        'facility_id' => $facility->id,
        'user_id' => $user->id,
        'status' => 'pending',
    ]);
});

test('petugas can mark facility status as dalam_perbaikan during processing and restore to aktif upon resolution (US 12)', function () {
    $petugas = User::factory()->petugas()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);
    $report = Report::factory()->create([
        'facility_id' => $facility->id,
        'status' => 'baru',
    ]);

    // 1. Petugas memproses laporan dan menandai fasilitas dalam perbaikan
    $response1 = $this->actingAs($petugas)->patch("/petugas/reports/{$report->id}", [
        'status' => 'diproses',
        'catatan_resolusi' => 'Sedang diperiksa oleh teknisi listrik.',
        'mark_facility_status' => 'dalam_perbaikan',
    ]);

    $response1->assertRedirect();
    expect($facility->fresh()->status)->toBe('dalam_perbaikan');

    // 2. Petugas menyelesaikan laporan dan mengembalikan fasilitas ke aktif
    $response2 = $this->actingAs($petugas)->patch("/petugas/reports/{$report->id}", [
        'status' => 'selesai',
        'catatan_resolusi' => 'Komponen rusak telah diganti baru dan diuji normal.',
        'mark_facility_status' => 'aktif',
    ]);

    $response2->assertRedirect();
    expect($facility->fresh()->status)->toBe('aktif');
});
