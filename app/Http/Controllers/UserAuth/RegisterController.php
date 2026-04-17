<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('user-auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_pengguna' => [
                'required',
                'unique:user,nama_pengguna',
                'max:60',
                'regex:/^\S*$/',
            ],
            'email' => 'required|email|max:100|unique:user,email',
            'nama_lengkap' => 'required|max:100',
        ], [
            'nama_pengguna.unique' => 'Nama pengguna sudah terdaftar, silakan gunakan nama lain.',
            'nama_pengguna.regex' => 'Nama pengguna tidak boleh mengandung spasi.',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
        ]);

        // Simpan data ke sesi sebelum verifikasi OTP
        $uid = substr(Str::uuid()->toString(), 0, 28);
        $otp = rand(100000, 999999);

        $registerData = [
            'uid' => $uid,
            'nama_pengguna' => $request->nama_pengguna,
            'email' => $request->email,
            'nama_lengkap' => $request->nama_lengkap,
            'otp' => $otp,
        ];

        Session::put('register_data', $registerData);

        // Kirim OTP via email
        $this->sendOtpEmail($request->email, $otp);

        return redirect()->route('verifikasi-akun')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    private function sendOtpEmail($email, $otp)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Penerima
            $mail->setFrom('', 'Media Explant');
            $mail->addAddress($email);

            // Konten email
            $mail->isHTML(true);
            $mail->Subject = 'Kode OTP Anda';
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f4f4f9; color: #333; }
                        .email-container { background-color: #ffffff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
                        .header { background-color: #ff4b5c; color: white; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
                        .content { padding: 20px; }
                        .otp-box { background-color: #f3f3f3; border: 1px solid #dcdcdc; padding: 10px; text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; }
                        .footer { text-align: center; font-size: 14px; color: #777; margin-top: 30px; }
                        .footer a { color: #ff4b5c; text-decoration: none; }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='header'>
                            <h2>Kode OTP Anda</h2>
                        </div>
                        <div class='content'>
                            <p>Halo,</p>
                            <p>Berikut adalah kode OTP Anda:</p>
                            <div class='otp-box'>$otp</div>
                            <p><strong>Catatan:</strong> Kode OTP ini hanya berlaku selama 10 menit.</p>
                        </div>
                        <div class='footer'>
                            <p>Tim Media Explant</p>
                        </div>
                    </div>
                </body>
                </html>
            ";

            $mail->send();
        } catch (Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email. Error: ' . $mail->ErrorInfo]);
        }
    }

    public function resendOtp(Request $request)
    {
        $registerData = Session::get('register_data');

        if (!$registerData || !isset($registerData['email'])) {
            return back()->withErrors(['email' => 'Sesi telah berakhir. Silakan daftar ulang.']);
        }

        $otp = rand(100000, 999999); // Generate OTP baru
        $registerData['otp'] = $otp;
        Session::put('register_data', $registerData); // Perbarui data di session

        // Kirim ulang OTP ke email
        $this->sendOtpEmail($registerData['email'], $otp);

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
