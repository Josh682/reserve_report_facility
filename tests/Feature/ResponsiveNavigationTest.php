<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pengguna layout renders root z-50 off-canvas drawer and z-40 backdrop on mobile/split-screen', function () {
    $user = User::factory()->pengguna()->create();

    $response = $this->actingAs($user)->get(route('pengguna.dashboard'));

    $response->assertOk();
    // Backdrop at root level with z-40
    $response->assertSee('id="pengguna-sidebar-backdrop"', false);
    $response->assertSee('z-40', false);
    // Drawer panel at root level with z-50
    $response->assertSee('id="pengguna-sidebar-panel"', false);
    $response->assertSee('z-50', false);
    $response->assertSee('-translate-x-full', false);
    // Toggle button in mobile topbar
    $response->assertSee('onclick="togglePenggunaSidebar()"', false);
    // Static desktop sidebar
    $response->assertSee('hidden lg:flex lg:w-64', false);
    // Navigation links are rendered
    $response->assertSee(route('facilities'), false);
    $response->assertSee(route('reservation'), false);
    $response->assertSee(route('report'), false);
});

test('petugas layout renders root z-50 off-canvas drawer and z-40 backdrop on mobile/split-screen', function () {
    $petugas = User::factory()->petugas()->create();

    $response = $this->actingAs($petugas)->get(route('petugas.dashboard'));

    $response->assertOk();
    // Backdrop at root level with z-40
    $response->assertSee('id="petugas-sidebar-backdrop"', false);
    $response->assertSee('z-40', false);
    // Drawer panel at root level with z-50
    $response->assertSee('id="petugas-sidebar-panel"', false);
    $response->assertSee('z-50', false);
    $response->assertSee('-translate-x-full', false);
    // Toggle button in mobile topbar
    $response->assertSee('onclick="togglePetugasSidebar()"', false);
    // Static desktop sidebar
    $response->assertSee('hidden lg:flex lg:w-64', false);
    // Navigation links are rendered
    $response->assertSee(route('petugas.reservations.index'), false);
    $response->assertSee(route('petugas.reports.index'), false);
});

test('admin dashboard renders root z-50 off-canvas drawer and z-40 backdrop on mobile/split-screen', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
    // Backdrop at root level with z-40
    $response->assertSee('id="admin-sidebar-backdrop"', false);
    $response->assertSee('z-40', false);
    // Drawer panel at root level with z-50
    $response->assertSee('id="admin-sidebar-panel"', false);
    $response->assertSee('z-50', false);
    $response->assertSee('-translate-x-full', false);
    // Toggle button in mobile topbar
    $response->assertSee('onclick="toggleSidebar()"', false);
    // Static desktop sidebar
    $response->assertSee('hidden lg:flex lg:w-64', false);
    // Navigation links are rendered
    $response->assertSee(route('admin.dashboard'), false);
    $response->assertSee(route('admin.facilities.index'), false);
    $response->assertSee(route('admin.users.index'), false);
    $response->assertSee(route('admin.rekap.index'), false);
});

test('admin layout views (such as rekapitulasi) render root z-50 off-canvas drawer and z-40 backdrop', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get(route('admin.rekap.index'));

    $response->assertOk();
    // Backdrop at root level with z-40
    $response->assertSee('id="admin-sidebar-backdrop"', false);
    $response->assertSee('z-40', false);
    // Drawer panel at root level with z-50
    $response->assertSee('id="admin-sidebar-panel"', false);
    $response->assertSee('z-50', false);
    $response->assertSee('-translate-x-full', false);
    // Toggle button in mobile topbar
    $response->assertSee('onclick="toggleSidebar()"', false);
    // Static desktop sidebar
    $response->assertSee('hidden lg:flex lg:w-64', false);
    // Navigation links are rendered
    $response->assertSee(route('admin.rekap.index'), false);
});
