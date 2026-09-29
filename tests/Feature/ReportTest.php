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
    $response->assertSee('id="photoPreviewContainer"', false);
    $response->assertSee('id="charCounter"', false);
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

test('server rejects report photo exceeding 2048 kilobytes even if client validation is bypassed', function () {
    $user = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create(['status' => 'aktif']);

    $largeFile = extension_loaded('gd')
        ? UploadedFile::fake()->image('damage-evidence.jpg')->size(2500)
        : UploadedFile::fake()->create('damage-evidence.jpg', 2500, 'image/jpeg');

    $response = $this->actingAs($user)->post('/reports', [
        'facility_id' => $facility->id,
        'category' => 'kerusakan',
        'description' => 'Sebagai contoh nyata di lapangan kampus, mahasiswa dapat melaporkan insiden tak terduga seperti gangguan kebersihan atau kabel proyektor yang digigit kucing liar yang menyelinap ke ruang kelas.',
        'photo' => $largeFile,
    ]);

    $response->assertSessionHasErrors('photo');
});

test('authenticated user can filter their report history by status (US 7)', function () {
    $user = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create(['nama' => 'Ruang Teater 1']);

    Report::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'deskripsi' => 'Kabel HDMI panggung putus',
        'status' => 'baru',
    ]);

    Report::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facility->id,
        'deskripsi' => 'Lampu sorot telah diganti baru',
        'status' => 'selesai',
    ]);

    // Akses tanpa filter (melihat keduanya)
    $responseAll = $this->actingAs($user)->get('/report');
    $responseAll->assertOk();
    $responseAll->assertSee('Kabel HDMI panggung putus');
    $responseAll->assertSee('Lampu sorot telah diganti baru');

    // Filter status baru
    $responseBaru = $this->actingAs($user)->get('/report?status=baru');
    $responseBaru->assertOk();
    $responseBaru->assertSee('Kabel HDMI panggung putus');
    $responseBaru->assertDontSee('Lampu sorot telah diganti baru');

    // Filter status selesai
    $responseSelesai = $this->actingAs($user)->get('/report?status=selesai');
    $responseSelesai->assertOk();
    $responseSelesai->assertSee('Lampu sorot telah diganti baru');
    $responseSelesai->assertDontSee('Kabel HDMI panggung putus');
});

test('authenticated user can search their report history by keyword and facility name (US 7)', function () {
    $user = User::factory()->pengguna()->verified()->create();
    $facilityA = Facility::factory()->create(['nama' => 'Laboratorium Multimedia']);
    $facilityB = Facility::factory()->create(['nama' => 'Auditorium Utama']);

    Report::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facilityA->id,
        'deskripsi' => 'Sebagai simulasi laporan sarana kampus, ada insiden seekor kucing liar menumpahkan wadah air di meja kontrol lab.',
        'status' => 'baru',
    ]);

    Report::factory()->create([
        'user_id' => $user->id,
        'facility_id' => $facilityB->id,
        'deskripsi' => 'AC utama auditorium bersuara bising dan kurang dingin.',
        'status' => 'diproses',
    ]);

    // Cari kata kunci deskripsi "kucing"
    $responseSearchDesc = $this->actingAs($user)->get('/report?search=kucing');
    $responseSearchDesc->assertOk();
    $responseSearchDesc->assertSee('insiden seekor kucing liar');
    $responseSearchDesc->assertDontSee('bersuara bising dan kurang dingin');

    // Cari nama fasilitas "Auditorium"
    $responseSearchFac = $this->actingAs($user)->get('/report?search=Auditorium');
    $responseSearchFac->assertOk();
    $responseSearchFac->assertSee('bersuara bising dan kurang dingin');
    $responseSearchFac->assertDontSee('insiden seekor kucing liar');
});

test('authenticated user sees accurate report status counts in view (US 7)', function () {
    $user = User::factory()->pengguna()->verified()->create();
    $facility = Facility::factory()->create();

    Report::factory()->create(['user_id' => $user->id, 'facility_id' => $facility->id, 'status' => 'baru']);
    Report::factory()->create(['user_id' => $user->id, 'facility_id' => $facility->id, 'status' => 'baru']);
    Report::factory()->create(['user_id' => $user->id, 'facility_id' => $facility->id, 'status' => 'diproses']);
    Report::factory()->create(['user_id' => $user->id, 'facility_id' => $facility->id, 'status' => 'selesai']);

    $response = $this->actingAs($user)->get('/report');
    $response->assertOk();
    $response->assertViewHas('counts', function ($counts) {
        return $counts['all'] === 4
            && $counts['baru'] === 2
            && $counts['diproses'] === 1
            && $counts['selesai'] === 1
            && $counts['ditolak'] === 0;
    });
});
