<?php

namespace App\Repositories\Contracts;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface AttendanceRepositoryInterface
{
    public function findOpenAttendanceForEmployee(Employee $employee): ?Attendance;

    public function create(array $data): Attendance;

    public function update(Attendance $attendance, array $data): Attendance;

    public function getDailyAttendances(Carbon $date): Collection;

    public function getEmployeeAttendances(Employee $employee, ?Carbon $date = null): Collection;
}
