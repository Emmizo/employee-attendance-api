Attendance {{ $type }} recorded for {{ $attendance->employee->name }} ({{ $attendance->employee->employee_identifier }}).

Checked in at: {{ optional($attendance->checked_in_at)->toDateTimeString() }}
Checked out at: {{ optional($attendance->checked_out_at)->toDateTimeString() }}

Notes: {{ $attendance->notes ?? '-' }}

