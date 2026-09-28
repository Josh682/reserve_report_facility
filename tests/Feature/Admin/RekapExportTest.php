<?php

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests cannot access export and print routes', function () {
    $this->get(route('admin.rekap.export-csv'))->assertRedirect(route('login'));
    $this->get(route('admin.rekap.export-excel'))->assertRedirect(route('login'));
    $this->get(route('admin.rekap.print'))->assertRedirect(route('login'));
});

test('non-admin users cannot access export and print routes', function () {
    $pengguna = User::factory()->pengguna()->verified()->create();
    $this->actingAs($pengguna)->get(route('admin.rekap.export-csv'))->assertForbidden();
    $this->actingAs($pengguna)->get(route('admin.rekap.export-excel'))->assertForbidden();
    $this->actingAs($pengguna)->get(route('admin.rekap.print'))->assertForbidden();

    $petugas = User::factory()->petugas()->verified()->create();
    $this->actingAs($petugas)->get(route('admin.rekap.export-csv'))->assertForbidden();
    $this->actingAs($petugas)->get(route('admin.rekap.export-excel'))->assertForbidden();
    $this->actingAs($petugas)->get(route('admin.rekap.print'))->assertForbidden();
});

test('admin can export rekapitulasi data to csv with UTF-8 BOM, valid headers, and structured tables', function () {
    $admin = User::factory()->admin()->create(['name' => 'Admin Utama']);
    $user = User::factory()->pengguna()->create(['name' => 'Rahmat Hidayat']);

    $facility = Facility::factory()->create([
        'nama' => 'Auditorium Gd A',
        'tipe' => 'aula',
        'lokasi' => 'Gedung Rektorat',
        'kapasitas' => 300,
    ]);

    $today = Carbon::today()->startOfMonth()->addDays(2)->toDateString();

    Reservation::factory()->approved()->create([
        'facility_id' => $facility->id,
        'user_id' => $user->id,
        'tanggal' => $today,
        'start_time' => '08:00',
        'end_time' => '10:30', // 2.5 jam
    ]);

    Report::factory()->resolved()->create([
        'facility_id' => $facility->id,
        'kategori' => 'kerusakan',
        'created_at' => Carbon::today()->startOfMonth()->addDays(2),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.rekap.export-csv'));

    $response->assertOk();

    // Verifikasi Header HTTP
    $contentType = $response->headers->get('content-type');
    expect($contentType)->toContain('text/csv');
    expect($contentType)->toContain('charset=UTF-8');

    $contentDisposition = $response->headers->get('content-disposition');
    expect($contentDisposition)->toContain('attachment');
    expect($contentDisposition)->toContain('filename="rekap-fasilitas-');
    expect($contentDisposition)->toEndWith('.csv"');

    // Verifikasi konten stream
    $content = $response->streamedContent();

    // Verifikasi UTF-8 BOM di awal output
    expect(str_starts_with($content, "\xEF\xBB\xBF"))->toBeTrue();

    // Verifikasi Info Header Periode & Metadata
    expect($content)->toContain('LAPORAN REKAPITULASI OKUPANSI FASILITAS & FREKUENSI KERUSAKAN');
    expect($content)->toContain('Periode Filter');
    expect($content)->toContain('Waktu Ekspor');
    expect($content)->toContain('Admin Utama');

    // Verifikasi Bagian A: Tabel Okupansi
    expect($content)->toContain('BAGIAN A: TABEL REKAPITULASI OKUPANSI FASILITAS');
    expect($content)->toContain('No,"Nama Fasilitas",Tipe,Lokasi,Kapasitas,"Total Booking","Total Jam Pakai","Pemesan Teraktif"');
    expect($content)->toContain('Auditorium Gd A');
    expect($content)->toContain('Aula');
    expect($content)->toContain('Gedung Rektorat');
    expect($content)->toContain('Rahmat Hidayat');

    // Verifikasi Bagian B: Tabel Kerusakan per Fasilitas
    expect($content)->toContain('BAGIAN B: TABEL FREKUENSI KERUSAKAN PER FASILITAS');
    expect($content)->toContain('No,"Nama Fasilitas",Lokasi,"Total Laporan",Fisik,Kebersihan,Lainnya,Selesai,"Belum Selesai","% Resolusi"');

    // Verifikasi Bagian C: Tabel Kerusakan per Lokasi/Gedung
    expect($content)->toContain('BAGIAN C: TABEL KERUSAKAN PER LOKASI / GEDUNG');
    expect($content)->toContain('No,"Lokasi Gedung","Total Insiden",Selesai,"% Resolusi"');
});

test('admin can export rekapitulasi data to excel with valid mime type and HTML/XML table structure', function () {
    $admin = User::factory()->admin()->create(['name' => 'Admin Kampus']);
    $user = User::factory()->pengguna()->create(['name' => 'Dewi Sartika']);

    $facility = Facility::factory()->create([
        'nama' => 'Lab Multimedia',
        'tipe' => 'laboratorium',
        'lokasi' => 'Gedung Informatika',
        'kapasitas' => 45,
    ]);

    $today = Carbon::today()->startOfMonth()->addDays(3)->toDateString();

    Reservation::factory()->approved()->create([
        'facility_id' => $facility->id,
        'user_id' => $user->id,
        'tanggal' => $today,
        'start_time' => '13:00',
        'end_time' => '16:00', // 3.0 jam
    ]);

    Report::factory()->create([
        'facility_id' => $facility->id,
        'kategori' => 'kebersihan',
        'status' => 'diproses',
        'created_at' => Carbon::today()->startOfMonth()->addDays(3),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.rekap.export-excel'));

    $response->assertOk();

    // Verifikasi Header MIME Excel
    $contentType = $response->headers->get('content-type');
    expect($contentType)->toContain('application/vnd.ms-excel');
    expect($contentType)->toContain('charset=UTF-8');

    $contentDisposition = $response->headers->get('content-disposition');
    expect($contentDisposition)->toContain('attachment');
    expect($contentDisposition)->toContain('filename="rekap-fasilitas-');
    expect($contentDisposition)->toEndWith('.xls"');

    // Verifikasi konten file excel (HTML/XML Table)
    $content = $response->getContent();
    expect($content)->toContain('<table');
    expect($content)->toContain('<tr');
    expect($content)->toContain('<th');
    expect($content)->toContain('<td');
    expect($content)->toContain('LAPORAN REKAPITULASI OKUPANSI FASILITAS &amp; FREKUENSI KERUSAKAN');
    expect($content)->toContain('BAGIAN A: TABEL REKAPITULASI OKUPANSI FASILITAS');
    expect($content)->toContain('BAGIAN B: TABEL FREKUENSI KERUSAKAN PER FASILITAS');
    expect($content)->toContain('BAGIAN C: TABEL KERUSAKAN PER LOKASI / GEDUNG');
    expect($content)->toContain('Lab Multimedia');
    expect($content)->toContain('Gedung Informatika');
    expect($content)->toContain('Dewi Sartika');
});

test('admin can access print preview with formal letterhead, KPI, tables, signature, and auto print script', function () {
    $admin = User::factory()->admin()->create(['name' => 'Siti Nurhaliza, S.Kom']);
    $user = User::factory()->pengguna()->create(['name' => 'Fajar Pratama']);

    $facility = Facility::factory()->create([
        'nama' => 'Ruang Teater Mini',
        'tipe' => 'aula',
        'lokasi' => 'Gedung Perpustakaan',
        'kapasitas' => 80,
    ]);

    $today = Carbon::today()->startOfMonth()->addDays(4)->toDateString();

    Reservation::factory()->approved()->create([
        'facility_id' => $facility->id,
        'user_id' => $user->id,
        'tanggal' => $today,
        'start_time' => '09:00',
        'end_time' => '12:00',
    ]);

    Report::factory()->resolved()->create([
        'facility_id' => $facility->id,
        'kategori' => 'kerusakan',
        'created_at' => Carbon::today()->startOfMonth()->addDays(4),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.rekap.print'));

    $response->assertOk();
    $response->assertViewIs('admin.rekap.print');
    $response->assertViewHasAll([
        'filterParams',
        'periodeLabel',
        'occupancyData',
        'occupancySummary',
        'damageData',
        'locationDamageData',
        'damageSummary',
    ]);

    // Verifikasi Kop Surat Formal Kampus
    $response->assertSee('Biro Sarana dan Prasarana');
    $response->assertSee('Universitas Sarana Prasarana Nusantara');

    // Verifikasi Judul Resmi
    $response->assertSee('LAPORAN REKAPITULASI OKUPANSI FASILITAS &amp; FREKUENSI KERUSAKAN', false);

    // Verifikasi Metadata
    $response->assertSee('Rentang Periode Laporan');
    $response->assertSee('Tanggal &amp; Waktu Cetak', false);
    $response->assertSee('Siti Nurhaliza, S.Kom');

    // Verifikasi KPI & Tabel
    $response->assertSee('Ruang Teater Mini');
    $response->assertSee('Gedung Perpustakaan');
    $response->assertSee('Bagian A: Rekapitulasi Okupansi Fasilitas');
    $response->assertSee('Bagian B: Frekuensi Kerusakan per Fasilitas');
    $response->assertSee('Bagian C: Tabel Kerusakan per Lokasi / Gedung');

    // Verifikasi Pengesahan Tanda Tangan Pimpinan
    $response->assertSee('Kepala Biro Sarana dan Prasarana');
    $response->assertSee('Dr. Ir. H. Bambang Sudarsono, M.T.');
    $response->assertSee('NIP. 19750812 200212 1 001');

    // Verifikasi Tombol dan Skrip Cetak
    $response->assertSee('Cetak Dokumen (Ctrl+P)');
    $response->assertSee('window.print()', false);
    $response->assertSee('@media print', false);
});

test('csv export respects active date range filter', function () {
    $admin = User::factory()->admin()->create();

    $facilityA = Facility::factory()->create(['nama' => 'Ruang Rapat A', 'lokasi' => 'Gedung A', 'tipe' => 'aula']);
    $facilityB = Facility::factory()->create(['nama' => 'Ruang Rapat B', 'lokasi' => 'Gedung B', 'tipe' => 'aula']);

    // Data Bulan Januari 2026
    Reservation::factory()->approved()->create([
        'facility_id' => $facilityA->id,
        'tanggal' => '2026-01-15',
        'start_time' => '08:00',
        'end_time' => '10:00', // 2 jam
    ]);
    Report::factory()->resolved()->create([
        'facility_id' => $facilityA->id,
        'kategori' => 'kerusakan',
        'created_at' => Carbon::parse('2026-01-15 10:00:00'),
    ]);

    // Data Bulan Februari 2026 (Di luar rentang Januari)
    Reservation::factory()->approved()->create([
        'facility_id' => $facilityB->id,
        'tanggal' => '2026-02-15',
        'start_time' => '09:00',
        'end_time' => '13:00', // 4 jam
    ]);
    Report::factory()->resolved()->create([
        'facility_id' => $facilityB->id,
        'kategori' => 'kebersihan',
        'created_at' => Carbon::parse('2026-02-15 10:00:00'),
    ]);

    // Filter khusus Januari 2026
    $response = $this->actingAs($admin)->get(route('admin.rekap.export-csv', [
        'preset' => 'custom',
        'start_date' => '2026-01-01',
        'end_date' => '2026-01-31',
    ]));

    $response->assertOk();
    $content = $response->streamedContent();

    expect($content)->toContain('01/01/2026 s/d 31/01/2026');

    // Ruang Rapat A ada reservasi 2 jam
    expect($content)->toMatch('/"Ruang Rapat A",Aula,"Gedung A",\d+,1,2/i');
    // Ruang Rapat B memiliki 0 booking pada periode Januari
    expect($content)->toMatch('/"Ruang Rapat B",Aula,"Gedung B",\d+,0,0/i');
});

test('excel export respects active date range filter', function () {
    $admin = User::factory()->admin()->create();

    $facilityA = Facility::factory()->create(['nama' => 'Aula Nusantara', 'lokasi' => 'Gedung Utama']);
    $facilityB = Facility::factory()->create(['nama' => 'Aula Mahameru', 'lokasi' => 'Gedung Timur']);

    // Data Maret 2026
    Reservation::factory()->approved()->create([
        'facility_id' => $facilityA->id,
        'tanggal' => '2026-03-10',
        'start_time' => '08:00',
        'end_time' => '11:00',
    ]);
    Report::factory()->resolved()->create([
        'facility_id' => $facilityA->id,
        'created_at' => Carbon::parse('2026-03-10 12:00:00'),
    ]);

    // Data April 2026
    Reservation::factory()->approved()->create([
        'facility_id' => $facilityB->id,
        'tanggal' => '2026-04-10',
        'start_time' => '08:00',
        'end_time' => '11:00',
    ]);
    Report::factory()->resolved()->create([
        'facility_id' => $facilityB->id,
        'created_at' => Carbon::parse('2026-04-10 12:00:00'),
    ]);

    // Request filter Maret 2026
    $response = $this->actingAs($admin)->get(route('admin.rekap.export-excel', [
        'preset' => 'custom',
        'start_date' => '2026-03-01',
        'end_date' => '2026-03-31',
    ]));

    $response->assertOk();
    $content = $response->getContent();

    expect($content)->toContain('01/03/2026 s/d 31/03/2026');
    expect($content)->toContain('Aula Nusantara');
    expect($content)->toContain('Aula Mahameru');
});

test('print view respects active date range filter', function () {
    $admin = User::factory()->admin()->create();

    $facility = Facility::factory()->create(['nama' => 'Studio Musik Kampus']);

    // Data Mei 2026
    Reservation::factory()->approved()->create([
        'facility_id' => $facility->id,
        'tanggal' => '2026-05-10',
        'start_time' => '10:00',
        'end_time' => '12:00',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.rekap.print', [
        'preset' => 'custom',
        'start_date' => '2026-05-01',
        'end_date' => '2026-05-31',
    ]));

    $response->assertOk();
    $response->assertSee('01/05/2026 — 31/05/2026');
    $response->assertSee('Studio Musik Kampus');
});

test('export endpoints support preset filter semua and 30_hari', function () {
    $admin = User::factory()->admin()->create();

    $csvSemua = $this->actingAs($admin)->get(route('admin.rekap.export-csv', ['preset' => 'semua']));
    $csvSemua->assertOk();
    expect($csvSemua->streamedContent())->toContain('Semua Periode');

    $excel30Hari = $this->actingAs($admin)->get(route('admin.rekap.export-excel', ['preset' => '30_hari']));
    $excel30Hari->assertOk();

    $printSemua = $this->actingAs($admin)->get(route('admin.rekap.print', ['preset' => 'semua']));
    $printSemua->assertOk();
    $printSemua->assertSee('Semua Periode');
});
