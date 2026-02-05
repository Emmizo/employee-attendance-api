<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Confirmation</title>
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

        .highlight-box {
            margin: 12px 0 20px;
            padding: 12px 16px;
            border-radius: 8px;
            background-color: #ecf5ff;
            border: 1px solid rgba(1, 88, 169, 0.18);
            font-size: 14px;
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
            <h1 class="header-title">Password Reset Successful</h1>
        </div>
        <div class="content">
            <p class="greeting">Hello {{ $user->name }},</p>

            <p class="lead">
                This email is to confirm that the password for your Employee Attendance account
                ({{ $user->email }}) has been successfully reset.
            </p>

            <div class="highlight-box">
                If you did <strong>not</strong> perform this action, please contact your administrator
                immediately and request another password reset.
            </div>

            <p class="lead" style="margin-bottom: 0;">
                Best regards,<br>
                The Employee Attendance Team
            </p>
        </div>
        <div class="footer">
            This is an automated security notification from the Employee Attendance system.
        </div>
    </div>
</div>
</body>
</html>
