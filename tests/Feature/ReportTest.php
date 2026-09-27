<?php

use App\Models\Facility;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('unauthenticated user is redirected to login when accessing report page', function () {
    $response = $this->get('/report');

    $response->assertRedirect('/login');
});

test('unverified user cannot submit report', function () {
    $user = User::factory()->pengguna()->pending()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);

    $response = $this->actingAs($user)->post('/reports', [
        'facility_id' => $facility->id,
        'category' => 'kerusakan',
        'description' => 'Lampu ruangan tidak menyala',
    ]);

    $response->assertForbidden();
});

test('authenticated verified user can view report page, facilities, and their reports', function () {
    $user = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create(['nama' => 'Lab Komputer AI']);

    $myReport = Report::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'deskripsi' => 'Pendingin ruangan AC mati total',
        'status' => 'baru',
    ]);

    $response = $this->actingAs($user)->get('/report');

    $response->assertOk();
    $response->assertSee('Lab Komputer AI');
    $response->assertSee('Pendingin ruangan AC mati total');
    $response->assertSee('Layanan Sarana & Prasarana Kampus', false);
});

test('authenticated user can submit report without photo successfully (US 6)', function () {
    $user = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);

    $payload = [
        'facility_id' => $facility->id,
        'category' => 'kerusakan',
        'description' => 'Proyektor ruang kelas bergaris-garis ungu.',
    ];

    $response = $this->actingAs($user)->post('/reports', $payload);

    $response->assertRedirect(route('report'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('reports', [
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'kategori' => 'kerusakan',
        'deskripsi' => 'Proyektor ruang kelas bergaris-garis ungu.',
        'status' => 'baru',
        'foto_path' => null,
    ]);
});

test('authenticated user can submit report with photo successfully (US 6)', function () {
    Storage::fake('public');

    $user = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);

    $file = UploadedFile::fake()->create('bukti_rusak.jpg', 1200, 'image/jpeg'); // 1.2MB

    $payload = [
        'facility_id' => $facility->id,
        'category' => 'kebersihan',
        'description' => 'Terdapat genangan air di lantai pojok belakang.',
        'photo' => $file,
    ];

    $response = $this->actingAs($user)->post('/reports', $payload);

    $response->assertRedirect(route('report'));
    $response->assertSessionHas('status');

    $report = Report::where('user_id', $user->id)->first();
    expect($report)->not->toBeNull()
        ->and($report->foto_path)->not->toBeNull();

    Storage::disk('public')->assertExists($report->foto_path);
});

test('submitting report fails if photo exceeds 2MB', function () {
    Storage::fake('public');

    $user = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);

    $heavyFile = UploadedFile::fake()->create('heavy.jpg', 2500, 'image/jpeg'); // 2.5MB > 2MB

    $payload = [
        'facility_id' => $facility->id,
        'category' => 'kerusakan',
        'description' => 'Kerusakan stopkontak listrik.',
        'photo' => $heavyFile,
    ];

    $response = $this->actingAs($user)->post('/reports', $payload);

    $response->assertSessionHasErrors(['photo']);
    $this->assertDatabaseEmpty('reports');
});

test('submitting report fails if photo is invalid mime type', function () {
    Storage::fake('public');

    $user = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);

    $pdfFile = UploadedFile::fake()->create('dokumen.pdf', 300, 'application/pdf');

    $payload = [
        'facility_id' => $facility->id,
        'category' => 'kerusakan',
        'description' => 'Kunci pintu kelas patah.',
        'photo' => $pdfFile,
    ];

    $response = $this->actingAs($user)->post('/reports', $payload);

    $response->assertSessionHasErrors(['photo']);
    $this->assertDatabaseEmpty('reports');
});

test('submitting report fails with invalid facility or short description', function () {
    $user = User::factory()->pengguna()->verified()->create();

    $response = $this->actingAs($user)->post('/reports', [
        'facility_id' => 9999, // non-existent
        'category' => 'kerusakan',
        'description' => 'abc', // < 5 chars
    ]);

    $response->assertSessionHasErrors(['facility_id', 'description']);
});
