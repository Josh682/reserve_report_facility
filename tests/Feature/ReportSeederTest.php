<?php

use App\Models\Report;
use Database\Seeders\FacilitySeeder;
use Database\Seeders\ReportSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('report seeder populates reports with complete status variations and sample images', function () {
    $this->seed(UserSeeder::class);
    $this->seed(FacilitySeeder::class);
    $this->seed(ReportSeeder::class);

    expect(Report::count())->toBeGreaterThanOrEqual(8);
    expect(Report::where('status', 'baru')->count())->toBeGreaterThanOrEqual(1);
    expect(Report::where('status', 'diproses')->count())->toBeGreaterThanOrEqual(1);
    expect(Report::where('status', 'selesai')->count())->toBeGreaterThanOrEqual(1);
    expect(Report::where('status', 'ditolak')->count())->toBeGreaterThanOrEqual(1);

    $reportWithPhoto = Report::whereNotNull('foto_path')->first();
    expect($reportWithPhoto)->not->toBeNull();
    expect(Storage::disk('public')->exists($reportWithPhoto->foto_path))->toBeTrue();
});

test('report seeder is idempotent and does not create duplicate entries on repeated run', function () {
    $this->seed(UserSeeder::class);
    $this->seed(FacilitySeeder::class);
    $this->seed(ReportSeeder::class);

    $initialCount = Report::count();

    $this->seed(ReportSeeder::class);

    expect(Report::count())->toBe($initialCount);
});
