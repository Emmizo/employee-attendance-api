<?php

use App\Models\Attendance;
use App\Models\Employee;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use App\Services\AttendanceService;
use App\Services\Contracts\AttendanceNotifierInterface;
use App\Services\Exceptions\AttendanceConflictException;
use Mockery as m;

afterEach(function () {
    m::close();
});

it('prevents double check-in', function () {
    $employee = new Employee(['email' => 'e@example.com']);
    $employee->id = 1;

    $attendances = m::mock(AttendanceRepositoryInterface::class);
    $attendances->shouldReceive('findOpenAttendanceForEmployee')->once()->andReturn(new Attendance());
    $attendances->shouldNotReceive('create');

    $notifier = m::mock(AttendanceNotifierInterface::class);
    $notifier->shouldNotReceive('notifyCheckedIn');

    $service = new AttendanceService($attendances, $notifier);

    $service->checkIn($employee);
})->throws(AttendanceConflictException::class);

it('prevents check-out when there is no open attendance', function () {
    $employee = new Employee(['email' => 'e@example.com']);
    $employee->id = 1;

    $attendances = m::mock(AttendanceRepositoryInterface::class);
    $attendances->shouldReceive('findOpenAttendanceForEmployee')->once()->andReturn(null);
    $attendances->shouldNotReceive('update');

    $notifier = m::mock(AttendanceNotifierInterface::class);
    $notifier->shouldNotReceive('notifyCheckedOut');

    $service = new AttendanceService($attendances, $notifier);

    $service->checkOut($employee);
})->throws(AttendanceConflictException::class);

it('checks in and notifies', function () {
    $employee = new Employee(['email' => 'e@example.com']);
    $employee->id = 1;

    $created = new Attendance(['employee_id' => 1]);

    $attendances = m::mock(AttendanceRepositoryInterface::class);
    $attendances->shouldReceive('findOpenAttendanceForEmployee')->once()->andReturn(null);
    $attendances->shouldReceive('create')->once()->andReturn($created);

    $notifier = m::mock(AttendanceNotifierInterface::class);
    $notifier->shouldReceive('notifyCheckedIn')->once()->with($created);

    $service = new AttendanceService($attendances, $notifier);

    $result = $service->checkIn($employee, 'note');

    expect($result)->toBe($created);
});

