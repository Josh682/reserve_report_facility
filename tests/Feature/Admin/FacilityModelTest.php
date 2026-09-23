<?php

use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('facility model can be created with factory and mass assigned', function () {
    $facility = Facility::factory()->create([
        'nama' => 'Laboratorium Rekayasa Perangkat Lunak',
        'tipe' => 'laboratorium',
        'lokasi' => 'Gedung E Lantai 3',
        'kapasitas' => 40,
        'deskripsi' => 'Lab komputer dengan spesifikasi tinggi.',
        'status' => 'aktif',
    ]);

    expect($facility->nama)->toBe('Laboratorium Rekayasa Perangkat Lunak')
        ->and($facility->tipe)->toBe('laboratorium')
        ->and($facility->lokasi)->toBe('Gedung E Lantai 3')
        ->and($facility->kapasitas)->toBe(40)
        ->and($facility->status)->toBe('aktif');
});

test('facility model scopes filter by status and tipe correctly', function () {
    Facility::factory()->create(['tipe' => 'aula', 'status' => 'aktif']);
    Facility::factory()->create(['tipe' => 'ruang_kelas', 'status' => 'dalam_perbaikan']);
    Facility::factory()->create(['tipe' => 'alat', 'status' => 'nonaktif']);

    expect(Facility::aktif()->count())->toBe(1)
        ->and(Facility::where('tipe', 'aula')->count())->toBe(1);
});
