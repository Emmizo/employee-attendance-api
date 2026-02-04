<?php

use App\Models\User;

it('logs in and returns a bearer token', function () {
    $user = User::factory()->create([
        'password' => bcrypt('Password123!'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'Password123!',
    ]);

    $response->assertOk();
    $response->assertJsonPath('data.user.email', $user->email);
    $response->assertJsonPath('data.token_type', 'Bearer');
    expect($response->json('data.token'))->toBeString()->not->toBeEmpty();
});

it('rejects invalid credentials', function () {
    $user = User::factory()->create([
        'password' => bcrypt('Password123!'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong',
    ]);

    $response->assertUnauthorized();
});

