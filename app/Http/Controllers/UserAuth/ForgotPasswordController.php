<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('user-auth.forgot_password');
    }

    public function sendOtp(Request $request)
    {
        // Validasi input email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->input('email');
        $otp = rand(100000, 999999); // Generate OTP 6 digit
        $otpExpiration = Carbon::now()->addMinutes(10); // Batas waktu OTP 10 menit

        // Kirim OTP ke email
        if ($this->sendOtpEmail($email, $otp)) {
            // Simpan OTP dan email di session
            Session::put('otp', $otp);
            Session::put('email', $email);
            Session::put('otp_expiration', $otpExpiration);

            return redirect()->route('password.verifyOtpForm')->with('status', 'Kode OTP telah dikirim ke email Anda.');
        }

        return back()->withErrors(['email' => 'Gagal mengirim OTP. Silakan coba lagi.']);
    }

    private function sendOtpEmail($email, $otp)
    {
        $mail = new PHPMailer(true);

        try {
            // Konfigurasi SMTP
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
            $mail->Subject = 'Kode OTP Anda untuk Reset Password';

            // Email Body yang lebih bagus
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
                            <div class=\"email-container\">
                                <div class=\"header\">
                                    <h2>Kode OTP Anda</h2>
                                </div>
                                <div class=\"content\">
                                    <p>Halo,</p>
                                    <p>Berikut adalah kode OTP Anda:</p>
                                    <div class=\"otp-box\">$otp</div>
                                    <p><strong>Catatan:</strong> Kode OTP ini hanya berlaku selama 10 menit.</p>
                                </div>
                                <div class=\"footer\">
                                    <p>Tim Media Explant</p>
                                </div>
                            </div>
                        </body>
                        </html>
                ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            Log::error('PHPMailer Error: ' . $mail->ErrorInfo);
            return false;
        }
    }

    public function showVerifyOtpForm()
    {
        return view('user-auth.verif_email');
    }

    public function verifyOtp(Request $request)
    {
        // Ambil OTP dari input form
        $inputOtpArray = $request->input('otp'); // Bisa berupa array
        $inputOtp = is_array($inputOtpArray) ? implode('', $inputOtpArray) : $inputOtpArray;

        // Validasi input OTP
        $validator = Validator::make(['otp' => $inputOtp], [
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $sessionOtp = Session::get('otp');
        $otpExpiration = Session::get('otp_expiration');

        if ($sessionOtp && $inputOtp == $sessionOtp) {
            if (Carbon::now()->lte($otpExpiration)) {
                return redirect()->route('password.changePasswordForm')->with('status', 'OTP berhasil diverifikasi. Silakan ganti password Anda.');
            } else {
                return back()->withErrors(['otp' => 'Kode OTP telah kadaluwarsa.']);
            }
        }

        return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
    }

    public function showChangePasswordForm()
    {
        return view('user-auth.change_password');
    }

    public function updatePassword(Request $request)
    {
        // Validasi input password
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $email = Session::get('email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi telah berakhir. Silakan ulangi proses.']);
        }

        // Cari user berdasarkan email dan update password
        $user = \App\Models\User::where('email', $email)->first();
        if ($user) {
            $user->password = bcrypt($request->input('password'));
            $user->save();

            // Hapus session terkait
            Session::forget(['otp', 'email', 'otp_expiration']);

            return redirect()->route('login')->with('status', 'Password Anda berhasil diubah. Silakan login.');
        }

        return back()->withErrors(['email' => 'Terjadi kesalahan. Silakan coba lagi.']);
    }

    public function resendOtp(Request $request)
    {
        $email = Session::get('email');

        if (!$email) {
            return back()->withErrors(['email' => 'Sesi telah berakhir. Silakan masukkan email kembali.']);
        }

        $otp = rand(100000, 999999); // Generate OTP baru
        $otpExpiration = Carbon::now()->addMinutes(10); // Batas waktu OTP 10 menit

        // Kirim ulang OTP ke email
        if ($this->sendOtpEmail($email, $otp)) {
            // Perbarui OTP di session
            Session::put('otp', $otp);
            Session::put('otp_expiration', $otpExpiration);

            return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
        }

        return back()->withErrors(['email' => 'Gagal mengirim ulang OTP. Silakan coba lagi.']);
    }
}
