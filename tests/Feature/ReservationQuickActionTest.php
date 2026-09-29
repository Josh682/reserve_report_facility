<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pengguna can open reservation form directly from quick action links', function () {
    $user = User::factory()->pengguna()->create();

    $response = $this->actingAs($user)->get(route('reservation', ['open_form' => 1]));

    $response->assertOk();
    $response->assertViewHas('openForm', true);
    $response->assertSee('id="reservationFormWrapper"', false);
});
