<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
    ) {
    }

    public function build(): self
    {
        return $this->subject('Password Reset Successful')
            ->view('emails.password-reset-confirmation-text')
            ->with([
                'user' => $this->user,
            ]);
    }
}
