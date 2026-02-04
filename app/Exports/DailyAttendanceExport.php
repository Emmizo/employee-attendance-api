<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyAttendanceExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly Carbon $date)
    {
    }

    public function collection(): Collection
    {
        return Attendance::query()
            ->with('employee')
            ->whereDate('checked_in_at', $this->date->toDateString())
            ->get()
            ->map(function (Attendance $attendance) {
                return [
                    'date' => $attendance->checked_in_at?->toDateString(),
                    'employee' => $attendance->employee->name,
                    'employee_identifier' => $attendance->employee->employee_identifier,
                    'checked_in_at' => $attendance->checked_in_at?->toDateTimeString(),
                    'checked_out_at' => $attendance->checked_out_at?->toDateTimeString(),
                    'notes' => $attendance->notes,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Employee',
            'Employee ID',
            'Checked in at',
            'Checked out at',
            'Notes',
        ];
    }
}

