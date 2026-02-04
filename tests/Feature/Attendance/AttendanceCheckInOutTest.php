<?php

use App\Mail\AttendanceRecordedMail;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;

it('requires authentication for attendance endpoints', function () {
    $this->postJson('/api/v1/attendance/check-in', [])->assertUnauthorized();
});

it('checks in and checks out an employee and queues emails', function () {
    Sanctum::actingAs(User::factory()->create());
    Mail::fake();

    $employee = Employee::factory()->create();

    $this->postJson('/api/v1/attendance/check-in', [
        'employee_identifier' => $employee->employee_identifier,
        'notes' => 'Arrived',
    ])->assertCreated();

    expect(Attendance::query()->where('employee_id', $employee->id)->count())->toBe(1);
    Mail::assertQueued(AttendanceRecordedMail::class, fn (AttendanceRecordedMail $mail) => $mail->type === 'check-in');

    $this->postJson('/api/v1/attendance/check-in', [
        'employee_identifier' => $employee->employee_identifier,
    ])->assertStatus(409);

    $this->postJson('/api/v1/attendance/check-out', [
        'employee_identifier' => $employee->employee_identifier,
        'notes' => 'Leaving',
    ])->assertOk();

    $attendance = Attendance::query()->where('employee_id', $employee->id)->firstOrFail();
    expect($attendance->checked_out_at)->not->toBeNull();
    Mail::assertQueued(AttendanceRecordedMail::class, fn (AttendanceRecordedMail $mail) => $mail->type === 'check-out');
});

it('prevents checking out if there is no open attendance', function () {
    Sanctum::actingAs(User::factory()->create());

    $employee = Employee::factory()->create();

    $this->postJson('/api/v1/attendance/check-out', [
        'employee_identifier' => $employee->employee_identifier,
    ])->assertStatus(409);
});

