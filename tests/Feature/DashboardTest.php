<?php

use App\Models\User;

test('guests are redirected to the login page from home', function () {
    $response = $this->get(route('home'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit home', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('home'));
    $response->assertOk();
});
