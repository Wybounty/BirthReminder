<?php

use App\Models\User;

test('authenticated users can visit the home page', function () {
    $response = $this->actingAs(User::factory()->create())
        ->get(route('home'));

    $response->assertOk();
});
