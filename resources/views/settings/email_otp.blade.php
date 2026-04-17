<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0; }
        .email-container { background-color: #ffffff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); width: 100%; max-width: 600px; margin: 0 auto; }
        h2 { color: #333; font-size: 28px; text-align: center; margin-bottom: 20px; }
        .otp-code { font-size: 40px; font-weight: bold; color: #007BFF; text-align: center; padding: 15px 0; background-color: #f0f8ff; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; font-size: 14px; color: #888; margin-top: 30px; }
        .footer a { color: #007BFF; text-decoration: none; }
        .footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Kode OTP Anda</h2>
        <p>Terima kasih telah menggunakan layanan kami. Berikut adalah Kode One-Time Password (OTP) Anda untuk verifikasi:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>Jika Anda tidak meminta OTP ini, silakan abaikan email ini.</p>
    </div>
</body>
</html>
