<?php

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view rekapitulasi dashboard with layout, hero banner, and US 17 badge', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

    $response->assertOk();
    $response->assertSee('Rekapitulasi Okupansi & Kerusakan Fasilitas — FacilityHub');
    $response->assertSee('Modul Analitik & Rekapitulasi — US 17');
    $response->assertSee('Rekapitulasi Okupansi & Kerusakan Fasilitas');
    $response->assertSee('Pemantauan terpadu utilisasi jam operasional fasilitas kampus');
});

test('displays summary KPI cards for both occupancy and damage metrics', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->pengguna()->create(['name' => 'Budi Hartono']);

    $facilityA = Facility::factory()->create([
        'nama' => 'Auditorium Utama',
        'lokasi' => 'Gedung Rektorat Lt 3',
        'tipe' => 'aula',
        'kapasitas' => 500,
    ]);

    $facilityB = Facility::factory()->create([
        'nama' => 'Lab Komputer A',
        'lokasi' => 'Gedung TI Lt 1',
        'tipe' => 'laboratorium',
        'kapasitas' => 40,
    ]);

    $today = Carbon::today()->startOfMonth()->addDays(2)->toDateString();

    // 1 approved reservation: 08:00 to 11:00 (3.0 hours)
    Reservation::factory()->approved()->create([
        'facility_id' => $facilityA->id,
        'user_id' => $user->id,
        'tanggal' => $today,
        'start_time' => '08:00',
        'end_time' => '11:00',
    ]);

    // Reports on facility A: 1 resolved 'kerusakan'
    Report::factory()->resolved()->create([
        'facility_id' => $facilityA->id,
        'kategori' => 'kerusakan',
        'created_at' => Carbon::today()->startOfMonth()->addDays(2),
    ]);

    // Report on facility B: 1 in-progress 'kebersihan'
    Report::factory()->inProgress()->create([
        'facility_id' => $facilityB->id,
        'kategori' => 'kebersihan',
        'created_at' => Carbon::today()->startOfMonth()->addDays(3),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

    $response->assertOk();

    // Occupancy KPI Cards
    $response->assertSee('Total Jam Pemakaian');
    $response->assertSee('3 jam');
    $response->assertSee('Total Reservasi Approved');
    $response->assertSee('Fasilitas Terfavorit');
    $response->assertSee('Auditorium Utama');

    // Damage KPI Cards
    $response->assertSee('Total Laporan Masuk');
    $response->assertSee('2'); // 2 reports total
    $response->assertSee('Selesai Ditangani');
    $response->assertSee('1'); // 1 resolved
    $response->assertSee('50%'); // 1/2 = 50%
    $response->assertSee('Lokasi Paling Rawan');
});

test('renders date range filter card with presets, custom date inputs, and hidden tab state', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.rekap.index', ['tab' => 'kerusakan']));

    $response->assertOk();
    $response->assertSee('Filter Waktu Cepat');
    $response->assertSee('Bulan Ini');
    $response->assertSee('30 Hari Terakhir');
    $response->assertSee('Semua Waktu');
    $response->assertSee('Tanggal Mulai');
    $response->assertSee('Tanggal Selesai');
    $response->assertSee('Terapkan Filter');
    $response->assertSee('kezak-input', false);
    $response->assertSee('id="start_date"', false);
    $response->assertSee('id="end_date"', false);
    $response->assertSee('name="tab"', false);
    $response->assertSee('value="kerusakan"', false);
});

test('renders interactive tabs and activates corresponding panel based on tab query param', function () {
    $admin = User::factory()->admin()->create();

    // Default or ?tab=okupansi
    $responseOkupansi = $this->actingAs($admin)->get(route('admin.rekap.index'));
    $responseOkupansi->assertOk();
    $responseOkupansi->assertSee('Rekap Okupansi Fasilitas Kampus');
    $responseOkupansi->assertSee('Rekap Frekuensi Kerusakan & Lokasi');
    $responseOkupansi->assertSee('id="tab-panel-okupansi" class="block space-y-4"', false);
    $responseOkupansi->assertSee('id="tab-panel-kerusakan" class="hidden space-y-7"', false);

    // ?tab=kerusakan
    $responseKerusakan = $this->actingAs($admin)->get(route('admin.rekap.index', ['tab' => 'kerusakan']));
    $responseKerusakan->assertOk();
    $responseKerusakan->assertSee('id="tab-panel-okupansi" class="hidden space-y-4"', false);
    $responseKerusakan->assertSee('id="tab-panel-kerusakan" class="block space-y-7"', false);
});

test('renders occupancy data table with all required columns and visual metrics', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->pengguna()->create(['name' => 'Dr. Suhartono, M.Kom']);

    $facility = Facility::factory()->create([
        'nama' => 'Graha Widya Utama',
        'lokasi' => 'Kampus Terpadu',
        'tipe' => 'aula',
        'kapasitas' => 800,
    ]);

    Reservation::factory()->approved()->create([
        'facility_id' => $facility->id,
        'user_id' => $user->id,
        'tanggal' => Carbon::today()->startOfMonth()->addDays(5)->toDateString(),
        'start_time' => '09:00',
        'end_time' => '12:00', // 3 hours
    ]);

    $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

    $response->assertOk();
    // Headers
    $response->assertSee('No');
    $response->assertSee('Nama Fasilitas & Tipe');
    $response->assertSee('Lokasi');
    $response->assertSee('Kapasitas');
    $response->assertSee('Total Booking');
    $response->assertSee('Total Jam Pemakaian');
    $response->assertSee('Pemesan Teraktif');

    // Row Data
    $response->assertSee('Graha Widya Utama');
    $response->assertSee('Kampus Terpadu');
    $response->assertSee('800 Orang');
    $response->assertSee('1 kali');
    $response->assertSee('3 jam');
    $response->assertSee('Dr. Suhartono, M.Kom');
});

test('renders damage sub-table A and sub-table B with all required columns', function () {
    $admin = User::factory()->admin()->create();

    $facility = Facility::factory()->create([
        'nama' => 'Laboratorium Jaringan Komputer',
        'lokasi' => 'Gedung TI Lt 2',
        'tipe' => 'laboratorium',
    ]);

    Report::factory()->resolved()->create([
        'facility_id' => $facility->id,
        'kategori' => 'kerusakan',
        'created_at' => Carbon::today()->startOfMonth()->addDay(),
    ]);

    Report::factory()->create([
        'facility_id' => $facility->id,
        'kategori' => 'kebersihan',
        'status' => 'baru',
        'created_at' => Carbon::today()->startOfMonth()->addDays(2),
    ]);

    $response = $this->actingAs($admin)->get(route('admin.rekap.index', ['tab' => 'kerusakan']));

    $response->assertOk();

    // Sub-tabel A: Frekuensi Kerusakan per Fasilitas
    $response->assertSee('Sub-tabel A: Frekuensi Kerusakan per Fasilitas');
    $response->assertSee('Fasilitas');
    $response->assertSee('Total Laporan');
    $response->assertSee('Kerusakan Fisik');
    $response->assertSee('Kebersihan');
    $response->assertSee('Lainnya');
    $response->assertSee('Status Selesai / Belum Selesai');
    $response->assertSee('% Resolusi');
    $response->assertSee('Laboratorium Jaringan Komputer');
    $response->assertSee('Gedung TI Lt 2');
    $response->assertSee('1 Selesai');
    $response->assertSee('1 Belum');

    // Sub-tabel B: Frekuensi Kerusakan per Lokasi/Gedung Kampus
    $response->assertSee('Sub-tabel B: Frekuensi Kerusakan per Lokasi/Gedung Kampus');
    $response->assertSee('Gedung / Lokasi');
    $response->assertSee('Total Insiden');
    $response->assertSee('Terselesaikan');
    $response->assertSee('2 insiden');
    $response->assertSee('1 selesai');
    $response->assertSee('50%');
});

test('renders multi-format export buttons preserving query filters and pdf target blank', function () {
    $admin = User::factory()->admin()->create();

    $params = [
        'preset' => '30_hari',
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-28',
        'tab' => 'kerusakan',
    ];

    $response = $this->actingAs($admin)->get(route('admin.rekap.index', $params));

    $response->assertOk();

    // CSV link
    $csvUrl = route('admin.rekap.export-csv', $params);
    $response->assertSee($csvUrl);
    $response->assertSee('Unduh CSV');

    // Excel link
    $excelUrl = route('admin.rekap.export-excel', $params);
    $response->assertSee($excelUrl);
    $response->assertSee('Unduh Excel (.xls)');

    // Print / PDF link
    $printUrl = route('admin.rekap.print', $params);
    $response->assertSee($printUrl);
    $response->assertSee('Cetak / Simpan PDF');
    $response->assertSee('target="_blank"', false);
});

test('handles empty dataset gracefully displaying descriptive empty states in both tabs', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

    $response->assertOk();
    $response->assertSee('Tidak ada data okupansi');
    $response->assertSee('Tidak ada riwayat kerusakan');
    $response->assertSee('Tidak ada riwayat per gedung');
    $response->assertSee('0 jam');
    $response->assertSee('0%');
    $response->assertSee('-');
});
