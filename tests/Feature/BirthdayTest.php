<?php

use App\Models\Birthday;
use App\Models\User;

test('authenticated users can add a birthday', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('birthday.store'), [
        'first_name' => 'Ada',
        'last_name' => 'Lovelace',
        'birth_date' => '1815-12-10',
        'email' => 'ada@example.com',
        'notes' => 'Pionnière de la programmation',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertDatabaseHas('birthdays', [
        'user_id' => $user->id,
        'first_name' => 'Ada',
        'last_name' => 'Lovelace',
    ]);
});

test('authenticated users can update only their own birthday', function () {
    $user = User::factory()->create();
    $birthday = Birthday::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->put(route('birthday.update', $birthday), [
        'first_name' => 'Grace',
        'last_name' => $birthday->last_name,
        'birth_date' => '1906-12-09',
        'email' => $birthday->email,
    ]);

    $response->assertRedirect(route('home'));
    expect($birthday->refresh()->first_name)->toBe('Grace');
});

test('users cannot update another users birthday', function () {
    $user = User::factory()->create();
    $birthday = Birthday::factory()->create(['user_id' => $user->id]);
    $otherUser = User::factory()->create();

    $this->actingAs($otherUser)
        ->put(route('birthday.update', $birthday), [
            'first_name' => 'Intrus',
            'last_name' => $birthday->last_name,
            'birth_date' => $birthday->birth_date->format('Y-m-d'),
            'email' => $birthday->email,
        ])
        ->assertNotFound();
});

test('birthday fields are validated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('birthday.store'), [])
        ->assertSessionHasErrors(['first_name', 'last_name', 'birth_date', 'email']);
});

test('a birthday cannot have a future birth date', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('birthday.store'), [
            'first_name' => 'Future',
            'last_name' => 'Person',
            'birth_date' => now()->addDay()->toDateString(),
            'email' => 'future@example.com',
        ])
        ->assertSessionHasErrors('birth_date');

    $this->assertDatabaseMissing('birthdays', ['email' => 'future@example.com']);
});
