<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
    ) {
    }

    public function build(): self
    {
        return $this->subject('Welcome to Employee Attendance API')
            ->view('emails.welcome-text')
            ->with([
                'user' => $this->user,
            ]);
    }
}
