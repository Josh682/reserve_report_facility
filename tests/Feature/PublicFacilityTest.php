<?php

use App\Models\Facility;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can access facilities catalog without authentication', function () {
    Facility::factory()->create([
        'nama' => 'Lab Multimedia 1',
        'status' => 'aktif',
    ]);

    $response = $this->get(route('facilities'));

    $response->assertOk()
        ->assertSee('Lab Multimedia 1')
        ->assertSee('Katalog & Ketersediaan Fasilitas');
});

test('guest can search facilities by keyword (US 2)', function () {
    Facility::factory()->create(['nama' => 'Aula Utama']);
    Facility::factory()->create(['nama' => 'Ruang Teori 1']);

    $response = $this->get(route('facilities', ['search' => 'Aula']));

    $response->assertOk()
        ->assertSee('Aula Utama')
        ->assertDontSee('Ruang Teori 1');
});

test('guest can filter facilities by type, location, and capacity (US 2)', function () {
    Facility::factory()->create([
        'nama' => 'Lab Jaringan',
        'tipe' => 'laboratorium',
        'lokasi' => 'Gedung C',
        'kapasitas' => 40,
    ]);

    Facility::factory()->create([
        'nama' => 'Ruang A101',
        'tipe' => 'ruang_kelas',
        'lokasi' => 'Gedung A',
        'kapasitas' => 20,
    ]);

    $response = $this->get(route('facilities', [
        'tipe' => 'laboratorium',
        'lokasi' => 'Gedung C',
        'kapasitas' => '30 - 50 orang',
    ]));

    $response->assertOk()
        ->assertSee('Lab Jaringan')
        ->assertDontSee('Ruang A101');
});

test('facility schedule produces exactly 26 fixed 30-minute slots between 07:00 and 20:00 (US 1)', function () {
    $facility = Facility::factory()->create(['status' => 'aktif']);
    $slots = $facility->getScheduleForDate(now()->toDateString());

    expect($slots)->toHaveCount(26)
        ->and($slots[0]['start'])->toBe('07:00')
        ->and($slots[0]['end'])->toBe('07:30')
        ->and($slots[25]['start'])->toBe('19:30')
        ->and($slots[25]['end'])->toBe('20:00');
});

test('guest sees slot marked unavailable when booked without exposing booker details (US 1 data privacy)', function () {
    $user = User::factory()->pengguna()->create([
        'name' => 'Budi Rahardjo Rahasia',
    ]);

    $facility = Facility::factory()->create(['status' => 'aktif']);

    Reservation::factory()->approved()->create([
        'facility_id' => $facility->id,
        'user_id' => $user->id,
        'tanggal' => now()->toDateString(),
        'start_time' => '08:00',
        'end_time' => '09:00', // covers 08:00-08:30 and 08:30-09:00
        'tujuan_penggunaan' => 'Rapat Rahasia Organisasi Kampus',
    ]);

    $slots = $facility->getScheduleForDate(now()->toDateString());

    // Slot 07:00 - 07:30 is available
    expect($slots[0]['is_available'])->toBeTrue();

    // Slot 08:00 - 08:30 is NOT available
    $slot0800 = collect($slots)->firstWhere('start', '08:00');
    expect($slot0800['is_available'])->toBeFalse()
        ->and($slot0800['status_label'])->toBe('Tidak Tersedia');

    // Slot 08:30 - 09:00 is NOT available
    $slot0830 = collect($slots)->firstWhere('start', '08:30');
    expect($slot0830['is_available'])->toBeFalse();

    // Slot 09:00 - 09:30 is available again
    $slot0900 = collect($slots)->firstWhere('start', '09:00');
    expect($slot0900['is_available'])->toBeTrue();

    // Verify through schedule endpoint
    $response = $this->getJson(route('facilities.schedule', [
        'facility' => $facility->id,
        'date' => now()->toDateString(),
    ]));

    $response->assertOk()
        ->assertJsonPath('facility.id', $facility->id)
        ->assertJsonPath('total_slots', 26)
        ->assertDontSee('Budi Rahardjo Rahasia')
        ->assertDontSee('Rapat Rahasia Organisasi Kampus');
});

test('all slots are marked unavailable when facility is in maintenance or inactive', function () {
    $maintenanceFacility = Facility::factory()->create(['status' => 'dalam_perbaikan']);
    $inactiveFacility = Facility::factory()->create(['status' => 'nonaktif']);

    $maintenanceSlots = $maintenanceFacility->getScheduleForDate(now()->toDateString());
    $inactiveSlots = $inactiveFacility->getScheduleForDate(now()->toDateString());

    foreach ($maintenanceSlots as $slot) {
        expect($slot['is_available'])->toBeFalse()
            ->and($slot['status_label'])->toBe('Dalam Perbaikan');
    }

    foreach ($inactiveSlots as $slot) {
        expect($slot['is_available'])->toBeFalse()
            ->and($slot['status_label'])->toBe('Nonaktif');
    }
});
