<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests cannot access admin rekap routes', function () {
    $this->get(route('admin.rekap.index'))->assertRedirect(route('login'));
    $this->get(route('admin.rekap.export-csv'))->assertRedirect(route('login'));
    $this->get(route('admin.rekap.export-excel'))->assertRedirect(route('login'));
    $this->get(route('admin.rekap.print'))->assertRedirect(route('login'));
});

test('non-admin users cannot access admin rekap routes', function () {
    $pengguna = User::factory()->pengguna()->verified()->create();
    $this->actingAs($pengguna)->get(route('admin.rekap.index'))->assertForbidden();

    $petugas = User::factory()->petugas()->verified()->create();
    $this->actingAs($petugas)->get(route('admin.rekap.index'))->assertForbidden();
});

test('admin can access rekap routes successfully', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get(route('admin.rekap.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.rekap.export-csv'))->assertOk();
    $this->actingAs($admin)->get(route('admin.rekap.export-excel'))->assertOk();
    $this->actingAs($admin)->get(route('admin.rekap.print'))->assertOk();
});

test('admin layout contains rekapitulasi & ekspor navigation link', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.facilities.index'));
    $response->assertOk()
        ->assertSee('Rekapitulasi & Ekspor', false)
        ->assertSee(route('admin.rekap.index'));
});
