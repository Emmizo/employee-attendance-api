<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset your password</title>
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

        .button-wrapper {
            margin: 20px 0 24px;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 999px;
            background-color: #0158A9;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .small-text {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
        }

        .code-box {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            background-color: #f3f4f6;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px;
            word-break: break-all;
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
            <h1 class="header-title">Reset your password</h1>
        </div>
        <div class="content">
            <p class="greeting">Hello {{ $name ?? $email }},</p>

            <p class="lead">
                We received a request to reset the password for your Employee Attendance account.
            </p>

            <p class="lead">
                Click the button below to open the password reset page. This link is unique to you and may expire after a short time.
            </p>

            <div class="button-wrapper">
                <a href="{{ $resetUrl }}" class="button">Reset password</a>
            </div>

            <p class="small-text">
                If the button does not work, copy and paste this link into your browser:
            </p>

            <div class="code-box">
                {{ $resetUrl }}
            </div>

            <p class="small-text">
                For API clients, your reset token is:
            </p>

            <div class="code-box">
                {{ $token }}
            </div>

            <p class="lead" style="margin-top: 18px; margin-bottom: 0;">
                If you did not request a password reset, you can safely ignore this email.
            </p>
        </div>
        <div class="footer">
            This is an automated message from the Employee Attendance system. Please do not reply to this email.
        </div>
    </div>
</div>
</body>
</html>

