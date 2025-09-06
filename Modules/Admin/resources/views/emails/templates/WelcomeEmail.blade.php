<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Arial, sans-serif;
            direction: rtl;
        }
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.7;
            padding: 20px;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: linear-gradient(135deg, #4a6fdc 0%, #3a57bb 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 35px;
        }
        .welcome {
            font-size: 18px;
            margin-bottom: 25px;
            color: #444;
            line-height: 1.8;
        }
        .credentials {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .credential-item {
            display: flex;
            margin-bottom: 15px;
            padding: 12px 15px;
            background: white;
            border-radius: 6px;
            border: 1px solid #e2e6ea;
        }
        .credential-item:last-child {
            margin-bottom: 0;
        }
        .credential-label {
            font-weight: 700;
            color: #4a6fdc;
            min-width: 140px;
        }
        .credential-value {
            font-weight: 500;
            color: #2c3e50;
            word-break: break-all;
        }
        .password {
            font-family: monospace;
            font-size: 16px;
            letter-spacing: 1px;
        }
        .login-button {
            display: block;
            background: #4a6fdc;
            color: #FFF;
            text-align: center;
            padding: 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
            margin: 30px 0;
            transition: background 0.3s;
        }
        .login-button:hover {
            background: #3a57bb;
        }
        .note {
            background: #fff9e6;
            border-right: 4px solid #ffc107;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .note h3 {
            color: #d39e00;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .footer {
            background: #f8f9fa;
            padding: 25px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
        }
        .logo {
            font-weight: 700;
            font-size: 22px;
            color: #4a6fdc;
            margin-bottom: 15px;
        }
        .support {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
        }
        @media (max-width: 650px) {
            .content {
                padding: 20px;
            }
            .credential-item {
                flex-direction: column;
            }
            .credential-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    {!! $content !!}
</body>
</html>
