<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

it('queues a password reset notification', function () {
    Notification::fake();

    $user = User::factory()->create();

    $response = $this->postJson('/api/v1/auth/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertOk();

    Notification::assertSentTo($user, App\Notifications\QueuedResetPasswordNotification::class);
    expect(new App\Notifications\QueuedResetPasswordNotification('token'))->toBeInstanceOf(ShouldQueue::class);
});

it('does not reveal whether a user exists', function () {
    Notification::fake();

    $response = $this->postJson('/api/v1/auth/forgot-password', [
        'email' => 'missing@example.com',
    ]);

    $response->assertOk();
    Notification::assertNothingSent();
});

