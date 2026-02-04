<?php

namespace App\Mail;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceRecordedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Attendance $attendance,
        public readonly string $type,
    ) {
    }

    public function build(): self
    {
        $subject = $this->type === 'check-out'
            ? 'Attendance check-out recorded'
            : 'Attendance check-in recorded';

        return $this->subject($subject)
            ->text('emails.attendance-recorded-text');
    }
}

