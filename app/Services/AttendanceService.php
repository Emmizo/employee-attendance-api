<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use App\Services\Contracts\AttendanceNotifierInterface;
use App\Services\Exceptions\AttendanceConflictException;

class AttendanceService
{
    public function __construct(
        private readonly AttendanceRepositoryInterface $attendances,
        private readonly AttendanceNotifierInterface $notifier,
    ) {
    }

    public function checkIn(Employee $employee, ?string $notes = null): Attendance
    {
        $openAttendance = $this->attendances->findOpenAttendanceForEmployee($employee);
        if ($openAttendance !== null) {
            throw new AttendanceConflictException('Employee already checked in.');
        }

        $attendance = $this->attendances->create([
            'employee_id' => $employee->id,
            'checked_in_at' => now(),
            'checked_out_at' => null,
            'notes' => $notes,
        ]);

        $this->notifier->notifyCheckedIn($attendance);

        return $attendance;
    }

    public function checkOut(Employee $employee, ?string $notes = null): Attendance
    {
        $openAttendance = $this->attendances->findOpenAttendanceForEmployee($employee);
        if ($openAttendance === null) {
            throw new AttendanceConflictException('Employee has no open attendance to check out.');
        }

        $attendance = $this->attendances->update($openAttendance, [
            'checked_out_at' => now(),
            'notes' => $notes ?? $openAttendance->notes,
        ]);

        $this->notifier->notifyCheckedOut($attendance);

        return $attendance;
    }
}

