<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QueuedResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $email = method_exists($notifiable, 'getEmailForPasswordReset')
            ? $notifiable->getEmailForPasswordReset()
            : ($notifiable->email ?? '');

        // Base URL for the reset page (frontend or API client UI).
        // You can override via PASSWORD_RESET_URL in .env, otherwise defaults to {APP_URL}/reset-password.
        $baseUrl = env('PASSWORD_RESET_URL', rtrim(config('app.url'), '/') . '/reset-password');

        $resetUrl = $baseUrl.'?token='.urlencode($this->token).'&email='.urlencode($email);

        return (new MailMessage)
            ->subject('Reset your Employee Attendance password')
            ->view('emails.password-reset-link', [
                'resetUrl' => $resetUrl,
                'token' => $this->token,
                'email' => $email,
                'name' => $notifiable->name ?? $email,
            ]);
    }
}

