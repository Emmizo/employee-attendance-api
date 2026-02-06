<?php

namespace App\Repositories\Eloquent;

use App\Models\Attendance;
use App\Models\Employee;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AttendanceRepository implements AttendanceRepositoryInterface
{
    public function findOpenAttendanceForEmployee(Employee $employee): ?Attendance
    {
        return Attendance::where('employee_id', $employee->id)
            ->whereNull('checked_out_at')
            ->first();
    }

    public function create(array $data): Attendance
    {
        return Attendance::create($data);
    }

    public function update(Attendance $attendance, array $data): Attendance
    {
        $attendance->update($data);
        return $attendance->fresh();
    }

    public function getDailyAttendances(Carbon $date): Collection
    {
        return Attendance::whereDate('checked_in_at', $date->toDateString())
            ->with('employee')
            ->orderByDesc('checked_in_at')
            ->get();
    }

    public function getEmployeeAttendances(Employee $employee, ?Carbon $date = null): Collection
    {
        $query = Attendance::where('employee_id', $employee->id);

        if ($date) {
            $query->whereDate('checked_in_at', $date->toDateString());
        }

        return $query->orderBy('checked_in_at', 'desc')->get();
    }
}
