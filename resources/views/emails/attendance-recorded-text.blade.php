<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance {{ ucfirst($type) }} Recorded</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f7fb;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            color: #1f2933;
        }

        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f5f7fb;
            padding: 24px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .header {
            background: #0158A9;
            padding: 20px 32px;
            color: #ffffff;
        }

        .header-title {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .content {
            padding: 24px 32px 28px;
        }

        .lead {
            margin: 0 0 14px;
            font-size: 15px;
            line-height: 1.6;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 18px;
            font-size: 14px;
        }

        .details-table th {
            text-align: left;
            padding: 8px 0;
            color: #6b7280;
            font-weight: 500;
            width: 36%;
        }

        .details-table td {
            padding: 8px 0;
            color: #111827;
        }

        .footer {
            padding: 16px 32px 20px;
            background-color: #f9fafb;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="header">
            <h1 class="header-title">
                Attendance {{ $type === 'check-out' ? 'Check-out' : 'Check-in' }} Recorded
            </h1>
        </div>
        <div class="content">
            <p class="lead">
                An attendance {{ $type === 'check-out' ? 'check-out' : 'check-in' }} has been recorded
                for <strong>{{ $attendance->employee->name }}</strong>
                ({{ $attendance->employee->employee_identifier }}).
            </p>

            <table class="details-table">
                <tr>
                    <th>Checked in at</th>
                    <td>{{ optional($attendance->checked_in_at)->toDateTimeString() ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Checked out at</th>
                    <td>{{ optional($attendance->checked_out_at)->toDateTimeString() ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Notes</th>
                    <td>{{ $attendance->notes ?? '—' }}</td>
                </tr>
            </table>

            <p class="lead" style="margin-bottom: 0;">
                If anything looks incorrect, please contact your administrator
                or update the record through the Employee Attendance system.
            </p>
        </div>
        <div class="footer">
            This notification was sent automatically by the Employee Attendance system.
        </div>
    </div>
</div>
</body>
</html>
