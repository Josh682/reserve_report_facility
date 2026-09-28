<?php

use App\Models\Facility;
use App\Models\Report;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to login when accessing rekapitulasi index', function () {
    $this->get(route('admin.rekap.index'))
        ->assertRedirect(route('login'));
});

test('non-admin users cannot access rekapitulasi index', function () {
    $pengguna = User::factory()->pengguna()->verified()->create();
    $this->actingAs($pengguna)
        ->get(route('admin.rekap.index'))
        ->assertForbidden();

    $petugas = User::factory()->petugas()->verified()->create();
    $this->actingAs($petugas)
        ->get(route('admin.rekap.index'))
        ->assertForbidden();
});

test('admin can access rekapitulasi index and view bound datasets', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

    $response->assertOk();
    $response->assertViewIs('admin.rekap.index');
    $response->assertViewHasAll([
        'filterParams',
        'occupancyData',
        'occupancySummary',
        'damageData',
        'locationDamageData',
        'damageSummary',
    ]);
});

test('accurately calculates occupancy duration and ranking for approved reservations only', function () {
    $admin = User::factory()->admin()->create();
    $userA = User::factory()->pengguna()->create(['name' => 'Budi Santoso']);
    $userB = User::factory()->pengguna()->create(['name' => 'Siti Nurhaliza']);

    $facilityA = Facility::factory()->create([
        'nama' => 'Auditorium Gd A',
        'lokasi' => 'Gedung Rektorat',
    ]);
    $facilityB = Facility::factory()->create([
        'nama' => 'Lab Komputer 1',
        'lokasi' => 'Gedung TI',
    ]);
    $facilityC = Facility::factory()->create([
        'nama' => 'Ruang Seminar',
        'lokasi' => 'Gedung Pasca',
    ]);

    // Current month dates
    $today = Carbon::today()->startOfMonth()->addDays(5)->toDateString();

    // Facility A: 2 approved reservations by userA
    // 08:00 to 10:00 (120 mins = 2.0 hours)
    Reservation::factory()->approved()->create([
        'facility_id' => $facilityA->id,
        'user_id' => $userA->id,
        'tanggal' => $today,
        'start_time' => '08:00',
        'end_time' => '10:00',
    ]);

    // 13:00 to 14:30 (90 mins = 1.5 hours)
    Reservation::factory()->approved()->create([
        'facility_id' => $facilityA->id,
        'user_id' => $userA->id,
        'tanggal' => $today,
        'start_time' => '13:00',
        'end_time' => '14:30',
    ]);

    // Non-approved reservations on Facility A (must be excluded)
    Reservation::factory()->create([
        'facility_id' => $facilityA->id,
        'user_id' => $userB->id,
        'tanggal' => $today,
        'status' => 'pending',
        'start_time' => '10:00',
        'end_time' => '12:00',
    ]);
    Reservation::factory()->rejected()->create([
        'facility_id' => $facilityA->id,
        'user_id' => $userB->id,
        'tanggal' => $today,
        'start_time' => '15:00',
        'end_time' => '17:00',
    ]);

    // Facility B: 1 approved reservation by userB (60 mins = 1.0 hour)
    Reservation::factory()->approved()->create([
        'facility_id' => $facilityB->id,
        'user_id' => $userB->id,
        'tanggal' => $today,
        'start_time' => '09:00',
        'end_time' => '10:00',
    ]);

    // Facility C has 0 reservations

    $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

    $response->assertOk();

    $occupancyData = $response->viewData('occupancyData');
    $occupancySummary = $response->viewData('occupancySummary');

    // Check Facility A metrics
    $itemA = $occupancyData->firstWhere('facility_id', $facilityA->id);
    expect($itemA)->not->toBeNull()
        ->and($itemA['total_reservasi'])->toBe(2)
        ->and($itemA['total_menit'])->toBe(210)
        ->and($itemA['total_jam'])->toBe(3.5)
        ->and($itemA['pemesan_terbanyak'])->toBe('Budi Santoso');

    // Check Facility B metrics
    $itemB = $occupancyData->firstWhere('facility_id', $facilityB->id);
    expect($itemB)->not->toBeNull()
        ->and($itemB['total_reservasi'])->toBe(1)
        ->and($itemB['total_menit'])->toBe(60)
        ->and($itemB['total_jam'])->toBe(1.0)
        ->and($itemB['pemesan_terbanyak'])->toBe('Siti Nurhaliza');

    // Check Facility C metrics
    $itemC = $occupancyData->firstWhere('facility_id', $facilityC->id);
    expect($itemC)->not->toBeNull()
        ->and($itemC['total_reservasi'])->toBe(0)
        ->and($itemC['total_menit'])->toBe(0)
        ->and($itemC['total_jam'])->toBe(0.0)
        ->and($itemC['pemesan_terbanyak'])->toBe('-');

    // Check summary card
    expect($occupancySummary['total_jam'])->toBe(4.5)
        ->and($occupancySummary['total_reservasi'])->toBe(3)
        ->and($occupancySummary['fasilitas_paling_sering'])->toBe('Auditorium Gd A')
        ->and($occupancySummary['fasilitas_terfavorit'])->toBe('Auditorium Gd A');
});

test('handles date filtering for preset bulan_ini, 30_hari, semua, and custom range', function () {
    $admin = User::factory()->admin()->create();
    $facility = Facility::factory()->create(['nama' => 'Aula Serbaguna']);

    // Reservation in current month
    Reservation::factory()->approved()->create([
        'facility_id' => $facility->id,
        'tanggal' => Carbon::today()->startOfMonth()->addDays(2)->toDateString(),
        'start_time' => '08:00',
        'end_time' => '10:00', // 2 hours
    ]);

    // Reservation 45 days ago (outside current month, outside 30_hari)
    Reservation::factory()->approved()->create([
        'facility_id' => $facility->id,
        'tanggal' => Carbon::today()->subDays(45)->toDateString(),
        'start_time' => '08:00',
        'end_time' => '11:00', // 3 hours
    ]);

    // Reservation 15 days ago (outside current month if early in month, but within 30_hari)
    Reservation::factory()->approved()->create([
        'facility_id' => $facility->id,
        'tanggal' => Carbon::today()->subDays(15)->toDateString(),
        'start_time' => '13:00',
        'end_time' => '14:00', // 1 hour
    ]);

    // 1. Default / bulan_ini
    $resBulanIni = $this->actingAs($admin)->get(route('admin.rekap.index'));
    $filterParams = $resBulanIni->viewData('filterParams');
    expect($filterParams['preset'])->toBe('bulan_ini')
        ->and($filterParams['start_date'])->toBe(Carbon::today()->startOfMonth()->toDateString())
        ->and($filterParams['end_date'])->toBe(Carbon::today()->endOfMonth()->toDateString());

    // 2. Preset 30_hari
    $res30Hari = $this->actingAs($admin)->get(route('admin.rekap.index', ['preset' => '30_hari']));
    $filter30 = $res30Hari->viewData('filterParams');
    expect($filter30['preset'])->toBe('30_hari')
        ->and($filter30['start_date'])->toBe(Carbon::today()->subDays(30)->toDateString())
        ->and($filter30['end_date'])->toBe(Carbon::today()->toDateString());

    // 3. Preset semua (all time)
    $resSemua = $this->actingAs($admin)->get(route('admin.rekap.index', ['preset' => 'semua']));
    $filterSemua = $resSemua->viewData('filterParams');
    $summarySemua = $resSemua->viewData('occupancySummary');
    expect($filterSemua['preset'])->toBe('semua')
        ->and($filterSemua['start_date'])->toBeNull()
        ->and($filterSemua['end_date'])->toBeNull()
        ->and($summarySemua['total_reservasi'])->toBe(3)
        ->and($summarySemua['total_jam'])->toBe(6.0);

    // 4. Custom range
    $startDate = Carbon::today()->subDays(50)->toDateString();
    $endDate = Carbon::today()->subDays(40)->toDateString();
    $resCustom = $this->actingAs($admin)->get(route('admin.rekap.index', [
        'preset' => 'custom',
        'start_date' => $startDate,
        'end_date' => $endDate,
    ]));
    $filterCustom = $resCustom->viewData('filterParams');
    $summaryCustom = $resCustom->viewData('occupancySummary');
    expect($filterCustom['preset'])->toBe('custom')
        ->and($filterCustom['start_date'])->toBe($startDate)
        ->and($filterCustom['end_date'])->toBe($endDate)
        ->and($summaryCustom['total_reservasi'])->toBe(1)
        ->and($summaryCustom['total_jam'])->toBe(3.0);
});

test('accurately aggregates damage frequency per facility and per building location', function () {
    $admin = User::factory()->admin()->create();

    $facA1 = Facility::factory()->create(['nama' => 'Lab Fisika', 'lokasi' => 'Gedung Sains']);
    $facA2 = Facility::factory()->create(['nama' => 'Lab Kimia', 'lokasi' => 'Gedung Sains']);
    $facB1 = Facility::factory()->create(['nama' => 'Perpustakaan Lt 1', 'lokasi' => 'Gedung Perpustakaan']);
    $facC1 = Facility::factory()->create(['nama' => 'Ruang Teater', 'lokasi' => 'Gedung Kesenian']);

    // Reports for FacA1 (Gedung Sains):
    // 1 report 'kerusakan' selesai
    Report::factory()->resolved()->create([
        'facility_id' => $facA1->id,
        'kategori' => 'kerusakan',
    ]);
    // 1 report 'kerusakan' diproses (in progress)
    Report::factory()->inProgress()->create([
        'facility_id' => $facA1->id,
        'kategori' => 'kerusakan',
    ]);
    // 1 report 'kebersihan' baru
    Report::factory()->create([
        'facility_id' => $facA1->id,
        'kategori' => 'kebersihan',
        'status' => 'baru',
    ]);
    // 1 report 'lainnya' ditolak
    Report::factory()->rejected()->create([
        'facility_id' => $facA1->id,
        'kategori' => 'lainnya',
    ]);

    // Reports for FacA2 (Gedung Sains):
    // 1 report 'kerusakan' selesai
    Report::factory()->resolved()->create([
        'facility_id' => $facA2->id,
        'kategori' => 'kerusakan',
    ]);

    // Reports for FacB1 (Gedung Perpustakaan):
    // 1 report 'kebersihan' baru
    Report::factory()->create([
        'facility_id' => $facB1->id,
        'kategori' => 'kebersihan',
        'status' => 'baru',
    ]);

    // FacC1 has 0 reports

    $response = $this->actingAs($admin)->get(route('admin.rekap.index', ['preset' => 'semua']));

    $response->assertOk();

    $damageData = $response->viewData('damageData');
    $locationDamageData = $response->viewData('locationDamageData');
    $damageSummary = $response->viewData('damageSummary');

    // 1. Check Facility A1 metrics
    $itemA1 = $damageData->firstWhere('facility_id', $facA1->id);
    expect($itemA1)->not->toBeNull()
        ->and($itemA1['total_laporan'])->toBe(4)
        ->and($itemA1['kerusakan'])->toBe(2)
        ->and($itemA1['kebersihan'])->toBe(1)
        ->and($itemA1['lainnya'])->toBe(1)
        ->and($itemA1['selesai'])->toBe(1)
        ->and($itemA1['belum_selesai'])->toBe(2) // baru + diproses
        ->and($itemA1['ditolak'])->toBe(1)
        ->and($itemA1['tingkat_penyelesaian'])->toBe(25.0); // 1 / 4 * 100

    // 2. Check Facility A2 metrics
    $itemA2 = $damageData->firstWhere('facility_id', $facA2->id);
    expect($itemA2)->not->toBeNull()
        ->and($itemA2['total_laporan'])->toBe(1)
        ->and($itemA2['selesai'])->toBe(1)
        ->and($itemA2['belum_selesai'])->toBe(0)
        ->and($itemA2['tingkat_penyelesaian'])->toBe(100.0);

    // 3. Check Location Aggregation
    // Gedung Sains should have total 5 reports (4 from A1 + 1 from A2)
    $sainsLoc = $locationDamageData->firstWhere('lokasi', 'Gedung Sains');
    expect($sainsLoc)->not->toBeNull()
        ->and($sainsLoc['total_laporan'])->toBe(5)
        ->and($sainsLoc['selesai'])->toBe(2)
        ->and($sainsLoc['belum_selesai'])->toBe(2)
        ->and($sainsLoc['fasilitas_count'])->toBe(2)
        ->and($sainsLoc['tingkat_penyelesaian'])->toBe(40.0); // 2 / 5 * 100

    $perpusLoc = $locationDamageData->firstWhere('lokasi', 'Gedung Perpustakaan');
    expect($perpusLoc)->not->toBeNull()
        ->and($perpusLoc['total_laporan'])->toBe(1)
        ->and($perpusLoc['selesai'])->toBe(0)
        ->and($perpusLoc['belum_selesai'])->toBe(1)
        ->and($perpusLoc['tingkat_penyelesaian'])->toBe(0.0);

    $kesenianLoc = $locationDamageData->firstWhere('lokasi', 'Gedung Kesenian');
    expect($kesenianLoc)->not->toBeNull()
        ->and($kesenianLoc['total_laporan'])->toBe(0)
        ->and($kesenianLoc['selesai'])->toBe(0)
        ->and($kesenianLoc['tingkat_penyelesaian'])->toBe(0.0);

    // Location Damage Data should be sorted by total_laporan desc
    expect($locationDamageData->first()['lokasi'])->toBe('Gedung Sains');

    // 4. Check Damage Summary Card
    expect($damageSummary['total_insiden'])->toBe(6)
        ->and($damageSummary['total_selesai'])->toBe(2)
        ->and($damageSummary['total_belum_selesai'])->toBe(3) // 2 from A1 + 1 from B1
        ->and($damageSummary['persentase_resolusi'])->toBe(33.3) // 2 / 6 * 100
        ->and($damageSummary['lokasi_paling_rawan'])->toBe('Gedung Sains');
});

test('handles empty dataset gracefully without errors or division by zero', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

    $response->assertOk();

    $occupancyData = $response->viewData('occupancyData');
    $occupancySummary = $response->viewData('occupancySummary');
    $damageData = $response->viewData('damageData');
    $locationDamageData = $response->viewData('locationDamageData');
    $damageSummary = $response->viewData('damageSummary');

    expect($occupancyData)->toBeEmpty()
        ->and($occupancySummary['total_jam'])->toBe(0.0)
        ->and($occupancySummary['total_reservasi'])->toBe(0)
        ->and($occupancySummary['fasilitas_paling_sering'])->toBe('-')
        ->and($damageData)->toBeEmpty()
        ->and($locationDamageData)->toBeEmpty()
        ->and($damageSummary['total_insiden'])->toBe(0)
        ->and($damageSummary['total_selesai'])->toBe(0)
        ->and($damageSummary['persentase_resolusi'])->toBe(0.0)
        ->and($damageSummary['lokasi_paling_rawan'])->toBe('-');
});

test('filters damage reports based on date parameters correctly', function () {
    $admin = User::factory()->admin()->create();
    $facility = Facility::factory()->create(['nama' => 'Lab Fisika', 'lokasi' => 'Gedung Sains']);

    // Report within current month
    Report::factory()->create([
        'facility_id' => $facility->id,
        'created_at' => Carbon::today()->startOfMonth()->addDays(2),
    ]);

    // Report from 60 days ago
    Report::factory()->create([
        'facility_id' => $facility->id,
        'created_at' => Carbon::today()->subDays(60),
    ]);

    // 1. Default (bulan_ini) - should only have 1 report
    $resDefault = $this->actingAs($admin)->get(route('admin.rekap.index'));
    $damageDefault = $resDefault->viewData('damageData');
    expect($damageDefault->first()['total_laporan'])->toBe(1);

    // 2. Preset semua - should have 2 reports
    $resSemua = $this->actingAs($admin)->get(route('admin.rekap.index', ['preset' => 'semua']));
    $damageSemua = $resSemua->viewData('damageData');
    expect($damageSemua->first()['total_laporan'])->toBe(2);
});
