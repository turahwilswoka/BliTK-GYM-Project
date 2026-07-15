<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password – Umah Dauh GYM</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #0f0f17;
            color: #e2e8f0;
            padding: 20px;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #0f0f17;
        }
        .header {
            background: linear-gradient(135deg, #ff8c00 0%, #ff4500 100%);
            border-radius: 16px 16px 0 0;
            padding: 40px 32px;
            text-align: center;
        }
        .logo-icon {
            font-size: 48px;
            margin-bottom: 12px;
            display: block;
        }
        .logo-title {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }
        .logo-subtitle {
            font-size: 13px;
            color: rgba(255,255,255,0.8);
            margin-top: 4px;
        }
        .body {
            background: #161622;
            padding: 40px 32px;
            border-left: 1px solid rgba(255,255,255,0.06);
            border-right: 1px solid rgba(255,255,255,0.06);
        }
        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 16px;
        }
        .text {
            font-size: 15px;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 12px;
        }
        .btn-container {
            text-align: center;
            margin: 36px 0;
        }
        .btn-reset {
            display: inline-block;
            background: linear-gradient(135deg, #ff8c00 0%, #ff4500 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.03em;
            box-shadow: 0 8px 24px rgba(255, 140, 0, 0.35);
        }
        .divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 28px 0;
        }
        .warning-box {
            background: rgba(234, 179, 8, 0.08);
            border: 1px solid rgba(234, 179, 8, 0.2);
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        .warning-box p {
            font-size: 13px;
            color: #fbbf24;
            line-height: 1.6;
        }
        .url-box {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 12px 16px;
            word-break: break-all;
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
        }
        .url-box a {
            color: #ff8c00;
            text-decoration: none;
        }
        .footer {
            background: #0a0a12;
            border-radius: 0 0 16px 16px;
            padding: 28px 32px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.06);
            border-top: none;
        }
        .footer p {
            font-size: 12px;
            color: #475569;
            line-height: 1.6;
        }
        .footer .brand {
            font-size: 14px;
            font-weight: 700;
            color: #ff8c00;
            margin-bottom: 8px;
        }
        @media (max-width: 600px) {
            .body { padding: 28px 20px; }
            .header { padding: 28px 20px; }
            .footer { padding: 20px; }
            .btn-reset { padding: 14px 28px; font-size: 14px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <span class="logo-icon">💪</span>
            <div class="logo-title">Umah Dauh GYM</div>
            <div class="logo-subtitle">Wujudkan Tubuh Ideal Anda</div>
        </div>

        <!-- Body -->
        <div class="body">
            <h1 class="greeting">Reset Password Anda 🔐</h1>

            <p class="text">
                Halo! Kami menerima permintaan untuk mereset password akun Umah Dauh GYM Anda yang terdaftar dengan email ini.
            </p>
            <p class="text">
                Klik tombol di bawah untuk membuat password baru. Tombol ini <strong style="color:#f1f5f9;">hanya berlaku selama 60 menit</strong>.
            </p>

            <div class="btn-container">
                <a href="{{ $url }}" class="btn-reset">
                    🔑 &nbsp; Reset Password Sekarang
                </a>
            </div>

            <div class="warning-box">
                <p>
                    ⚠️ <strong>Tidak merasa meminta reset password?</strong><br>
                    Abaikan email ini. Password Anda tetap aman dan tidak ada perubahan yang terjadi.
                </p>
            </div>

            <hr class="divider">

            <p class="text" style="font-size:13px;">
                Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:
            </p>
            <div class="url-box">
                <a href="{{ $url }}">{{ $url }}</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="brand">💪 Umah Dauh GYM</p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.<br>
            © {{ date('Y') }} Umah Dauh GYM. Seluruh hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
