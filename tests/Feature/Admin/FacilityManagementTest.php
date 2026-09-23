<?php

use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('admin can view facilities list with search and filters', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);
    Facility::factory()->create(['nama' => 'Lab Komputer A', 'tipe' => 'laboratorium', 'status' => 'aktif']);
    Facility::factory()->create(['nama' => 'Aula Utama', 'tipe' => 'aula', 'status' => 'dalam_perbaikan']);

    $response = $this->actingAs($admin)->get('/admin/facilities?search=Komputer');
    $response->assertStatus(200);
    $response->assertSee('Lab Komputer A');
    $response->assertDontSee('Aula Utama');
});

test('admin can view create facility form', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);

    $response = $this->actingAs($admin)->get('/admin/facilities/create');

    $response->assertStatus(200);
    $response->assertViewIs('admin.facilities.create');
});

test('admin can create facility with valid data', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);

    $response = $this->actingAs($admin)->post('/admin/facilities', [
        'nama' => 'Ruang Kuliah 101',
        'tipe' => 'ruang_kelas',
        'lokasi' => 'Gedung B Lantai 1',
        'kapasitas' => 45,
        'deskripsi' => 'Ruang kuliah lengkap dengan proyektor.',
        'status' => 'aktif',
    ]);

    $this->assertDatabaseHas('facilities', [
        'nama' => 'Ruang Kuliah 101',
        'tipe' => 'ruang_kelas',
        'kapasitas' => 45,
    ]);

    $response->assertRedirect(route('admin.facilities.index'));
    $response->assertSessionHas('status');
});

test('facility creation fails with invalid data', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);

    $response = $this->actingAs($admin)->post('/admin/facilities', [
        'nama' => '',
        'tipe' => 'invalid-tipe',
        'lokasi' => '',
        'status' => 'invalid-status',
    ]);

    $response->assertSessionHasErrors(['nama', 'tipe', 'lokasi', 'status']);
});

test('admin can view edit facility form', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);
    $facility = Facility::factory()->create();

    $response = $this->actingAs($admin)->get("/admin/facilities/{$facility->id}/edit");

    $response->assertStatus(200);
    $response->assertViewIs('admin.facilities.edit');
    $response->assertViewHas('facility', $facility);
});

test('admin can update an existing facility', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);
    $facility = Facility::factory()->create([
        'nama' => 'Ruang Kelas Lama',
        'tipe' => 'ruang_kelas',
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($admin)->put("/admin/facilities/{$facility->id}", [
        'nama' => 'Ruang Kelas Baru',
        'tipe' => 'ruang_kelas',
        'lokasi' => 'Gedung C Lantai 2',
        'kapasitas' => 50,
        'deskripsi' => 'Diperbarui',
        'status' => 'dalam_perbaikan',
    ]);

    $this->assertDatabaseHas('facilities', [
        'id' => $facility->id,
        'nama' => 'Ruang Kelas Baru',
        'status' => 'dalam_perbaikan',
    ]);

    $response->assertRedirect(route('admin.facilities.index'));
});

test('admin can toggle facility status quickly', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);
    $facility = Facility::factory()->create(['status' => 'aktif']);

    $response = $this->actingAs($admin)->patch("/admin/facilities/{$facility->id}/status", [
        'status' => 'nonaktif',
    ]);

    $this->assertDatabaseHas('facilities', [
        'id' => $facility->id,
        'status' => 'nonaktif',
    ]);

    $response->assertSessionHas('status');
});

test('admin can delete a facility without reservation history', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);
    $facility = Facility::factory()->create();

    $response = $this->actingAs($admin)->delete("/admin/facilities/{$facility->id}");

    $this->assertDatabaseMissing('facilities', ['id' => $facility->id]);
    $response->assertRedirect(route('admin.facilities.index'));
});

test('admin cannot delete a facility with reservation history', function () {
    $admin = User::factory()->create(['role' => 'admin', 'status_akun' => 'verified']);
    $facility = Facility::factory()->create();

    // Insert dummy reservation directly into reservations table
    DB::table('reservations')->insert([
        'user_id' => $admin->id,
        'facility_id' => $facility->id,
        'tanggal' => now()->addDays(2)->toDateString(),
        'start_time' => '08:00',
        'end_time' => '10:00',
        'tujuan_penggunaan' => 'Kegiatan Kuliah',
        'status' => 'approved',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $response = $this->actingAs($admin)->delete("/admin/facilities/{$facility->id}");

    $this->assertDatabaseHas('facilities', ['id' => $facility->id]);
    $response->assertSessionHas('status_error');
});

test('non-admin cannot access facility management', function () {
    $pengguna = User::factory()->create(['role' => 'pengguna', 'status_akun' => 'verified']);

    $this->actingAs($pengguna)->get('/admin/facilities')->assertStatus(403);
    $this->actingAs($pengguna)->post('/admin/facilities', [])->assertStatus(403);
});
