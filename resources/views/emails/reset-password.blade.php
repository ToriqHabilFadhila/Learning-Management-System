<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/LMS.png') }}" type="image/png">
    <title>Learning Management System Berbasis AI</title>
    @vite(['resources/css/app.css'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #ffffff;
            padding: 40px 20px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .logo-container {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            border-radius: 50%;
            background-color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .logo-container img {
            width: 90%;
            height: 90%;
            object-fit: contain;
            border-radius: 50%;
        }

        .email-content {
            padding: 40px 30px;
            background-color: #ffffff;
        }

        .greeting {
            font-size: 20px;
            color: #333333;
            margin-bottom: 20px;
            text-align: center;
        }

        .greeting strong {
            color: #2563eb;
        }

        .message {
            color: #666666;
            margin-bottom: 30px;
            font-size: 15px;
            line-height: 1.6;
            text-align: center;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .btn-reset {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff !important;
            padding: 14px 40px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            transition: background-color 0.3s ease;
        }

        .btn-reset:hover {
            background-color: #1d4ed8;
        }

        .warning-box {
            background-color: #fff8e1;
            border-left: 4px solid #ffa726;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
            font-size: 14px;
            color: #666666;
        }

        .email-footer {
            background-color: #fafafa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .email-footer p {
            margin: 8px 0;
            color: #999999;
            font-size: 13px;
            line-height: 1.6;
        }

        .email-footer strong {
            color: #333333;
            font-size: 15px;
        }

        @media only screen and (max-width: 600px) {
            .email-content,
            .email-footer {
                padding: 30px 20px;
            }

            .btn-reset {
                padding: 12px 32px;
                font-size: 14px;
            }

            .greeting {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <div class="logo-container">
                <img src="{{ $message->embed(public_path('images/LMS.png')) }}" alt="LMS Logo">
            </div>
        </div>

        <!-- Content -->
        <div class="email-content">
            <p class="greeting">Halo, Kepada <strong>{{ $email }}</strong></p>
            <p class="message">
                Kami menerima permintaan untuk mereset password akun Anda di <strong>Learning Management System</strong>.
                Klik tombol di bawah ini untuk membuat password baru.
            </p>
            <!-- Button -->
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="btn-reset">Reset Password</a>
            </div>
            <!-- Warning -->
            <div class="warning-box">
                <strong>Perhatian:</strong> Link ini akan kedaluwarsa dalam 60 menit.
            </div>
        </div>
        <!-- Footer -->
        <div class="email-footer" style=" background:#f9fafb; padding:28px 40px; text-align:center; border-top:1px solid #e5e7eb;">
            <p style=" margin:0; font-size:16px; font-weight:700; color:#111827;">
                Learning Management System
            </p>
            <p style=" margin:12px 0 0; font-size:13px; color:#6b7280; line-height:1.6;">
                Email ini dikirim secara otomatis untuk membantu mengamankan akun Anda.
                Jika Anda tidak meminta reset password, silakan abaikan email ini.
            </p>
            <p style=" margin:16px 0 0; font-size:12px; color:#9ca3af;">
                © {{ date('Y') }} LMS · All rights reserved
            </p>
        </div>
    </div>
</body>
</html>
