<?php

use App\Http\Requests\Admin\FacilityRequest;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

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

test('facility request validation passes with valid attributes', function () {
    $request = new FacilityRequest;
    $validator = Validator::make([
        'nama' => 'Ruang Seminar 1',
        'tipe' => 'aula',
        'lokasi' => 'Gedung A Lantai 2',
        'kapasitas' => 50,
        'deskripsi' => 'Ruangan ber-AC dengan proyektor.',
        'status' => 'aktif',
    ], $request->rules());

    expect($validator->passes())->toBeTrue();
});

test('facility request validation fails when required attributes are missing or invalid', function () {
    $request = new FacilityRequest;
    $validator = Validator::make([
        'nama' => '',
        'tipe' => 'invalid-type',
        'lokasi' => '',
        'kapasitas' => 0,
        'status' => 'invalid-status',
    ], $request->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('nama'))->toBeTrue()
        ->and($validator->errors()->has('tipe'))->toBeTrue()
        ->and($validator->errors()->has('lokasi'))->toBeTrue()
        ->and($validator->errors()->has('kapasitas'))->toBeTrue()
        ->and($validator->errors()->has('status'))->toBeTrue();
});
