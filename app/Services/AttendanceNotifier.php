<?php

namespace App\Services;

use App\Mail\AttendanceRecordedMail;
use App\Models\Attendance;
use App\Services\Contracts\AttendanceNotifierInterface;
use Illuminate\Support\Facades\Mail;

class AttendanceNotifier implements AttendanceNotifierInterface
{
    public function notifyCheckedIn(Attendance $attendance): void
    {
        $attendance->loadMissing('employee');

        Mail::to($attendance->employee->email)->queue(
            new AttendanceRecordedMail($attendance, 'check-in')
        );
    }

    public function notifyCheckedOut(Attendance $attendance): void
    {
        $attendance->loadMissing('employee');

        Mail::to($attendance->employee->email)->queue(
            new AttendanceRecordedMail($attendance, 'check-out')
        );
    }
}

