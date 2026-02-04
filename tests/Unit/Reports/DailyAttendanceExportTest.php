<?php

use App\Exports\DailyAttendanceExport;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('prepares daily attendance export data correctly', function () {
    $date = Carbon::parse('2026-02-04');

    $employee = Employee::factory()->create([
        'name' => 'John Doe',
        'employee_identifier' => 'EMP-12345',
    ]);

    Attendance::factory()->create([
        'employee_id' => $employee->id,
        'checked_in_at' => $date->copy()->setTime(9, 0),
        'checked_out_at' => $date->copy()->setTime(17, 0),
        'notes' => 'On time',
    ]);

    $export = new DailyAttendanceExport($date);
    $collection = $export->collection();

    expect($collection)->toHaveCount(1);

    $row = $collection->first();
    expect($row['date'])->toBe($date->toDateString());
    expect($row['employee'])->toBe('John Doe');
    expect($row['employee_identifier'])->toBe('EMP-12345');
    expect($row['notes'])->toBe('On time');

    expect($export)->toBeInstanceOf(WithHeadings::class);
    expect($export->headings())->toBeArray()->not->toBeEmpty();
});

