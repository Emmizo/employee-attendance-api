<?php

use App\Models\User;

it('registers a user and returns a bearer token', function () {
    $payload = [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ];

    $response = $this->postJson('/api/v1/auth/register', $payload);

    $response->assertCreated();
    $response->assertJsonPath('data.user.email', 'jane@example.com');
    $response->assertJsonPath('data.token_type', 'Bearer');
    expect($response->json('data.token'))->toBeString()->not->toBeEmpty();

    expect(User::query()->where('email', 'jane@example.com')->exists())->toBeTrue();
});

it('validates registration input', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => '',
        'email' => 'not-an-email',
        'password' => 'short',
        'password_confirmation' => 'mismatch',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['name', 'email', 'password']);
});

