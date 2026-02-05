<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Employee Attendance</title>
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

        .greeting {
            margin: 0 0 12px;
            font-size: 16px;
        }

        .lead {
            margin: 0 0 16px;
            font-size: 15px;
            line-height: 1.6;
        }

        .section-title {
            margin: 20px 0 8px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #0158A9;
        }

        .list {
            margin: 0 0 16px;
            padding-left: 18px;
            font-size: 14px;
            line-height: 1.6;
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
            <h1 class="header-title">Welcome to Employee Attendance</h1>
        </div>
        <div class="content">
            <p class="greeting">Hello {{ $user->name }},</p>

            <p class="lead">
                Your account has been successfully created with the email
                <strong>{{ $user->email }}</strong>.
            </p>

            <p class="lead">
                You can now use the Employee Attendance API to:
            </p>

            <p class="section-title">What you can do</p>
            <ul class="list">
                <li>Record daily check-in and check-out times</li>
                <li>View your attendance history and daily reports</li>
                <li>Keep your profile information up to date</li>
            </ul>

            <p class="lead">
                If you did not request this account, please contact your administrator.
            </p>

            <p class="lead" style="margin-bottom: 0;">
                Best regards,<br>
                The Employee Attendance Team
            </p>
        </div>
        <div class="footer">
            This is an automated message from the Employee Attendance system. Please do not reply to this email.
        </div>
    </div>
</div>
</body>
</html>
