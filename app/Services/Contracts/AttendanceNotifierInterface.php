<?php

namespace App\Services\Contracts;

use App\Models\Attendance;

interface AttendanceNotifierInterface
{
    public function notifyCheckedIn(Attendance $attendance): void;

    public function notifyCheckedOut(Attendance $attendance): void;
}

