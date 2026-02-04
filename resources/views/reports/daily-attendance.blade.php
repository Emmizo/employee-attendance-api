<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Attendance - {{ $date->toDateString() }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Daily Attendance - {{ $date->toDateString() }}</h1>

    <table>
        <thead>
        <tr>
            <th>Employee</th>
            <th>Employee ID</th>
            <th>Checked in at</th>
            <th>Checked out at</th>
            <th>Notes</th>
        </tr>
        </thead>
        <tbody>
        @forelse($attendances as $attendance)
            <tr>
                <td>{{ $attendance->employee->name }}</td>
                <td>{{ $attendance->employee->employee_identifier }}</td>
                <td>{{ optional($attendance->checked_in_at)->toDateTimeString() }}</td>
                <td>{{ optional($attendance->checked_out_at)->toDateTimeString() }}</td>
                <td>{{ $attendance->notes }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No attendance records for this date.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>

