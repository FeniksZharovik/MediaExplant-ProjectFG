<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function showSettings()
    {
        return view('settings.akun');
    }

    public function sendOtpToCurrentEmail(Request $request)
    {
        $user = session('user');

        if (!$user || !$user->email) {
            return back()->with('error', 'Email tidak ditemukan.');
        }

        $otp = rand(100000, 999999);

        // Simpan OTP ke session
        Session::put('otpFor', 'currentEmail');
        Session::put('otp_code', (string) $otp); // konsisten pakai otp_code
        Session::put('otpEmail', $user->email);

        // Kirim OTP via email
        if (!$this->sendOTP($user->email, $otp)) {
            return back()->with('error', 'Gagal mengirim kode OTP.');
        }

        // Tampilkan modal OTP
        return back()
            ->with('success', 'Kode OTP telah dikirim ke email Anda.')
            ->with('otpFor', 'currentEmail')
            ->with('showOtpModal', true);
    }

    public function verifyOtp(Request $request)
    {
        $otpInput = implode('', $request->otp ?? []);
        $sessionOtp = Session::get('otp_code'); // konsisten
        $otpFor = Session::get('otpFor');

        // Debug sementara
        // dd(['input' => $otpInput, 'session' => $sessionOtp]);

        if ($otpInput === $sessionOtp) {
            Session::forget('otp_code');
            Session::put('otpVerified', true);

            return redirect()->back()
                ->with('showNewEmailModal', true)
                ->with('success', 'Kode OTP berhasil diverifikasi.')
                ->with('otpFor', $otpFor);
        }

        return redirect()->back()
            ->with('otpError', 'Kode OTP salah.')
            ->with('showOtpModal', true);
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'newEmail' => 'required|email',
            'currentPassword' => 'required',
        ]);

        $sessionUser = session('user');

        if (!$sessionUser) {
            return back()->with('error', 'User tidak ditemukan.')->with('showNewEmailModal', true);
        }

        $user = \App\Models\User::where('uid', $sessionUser->uid)->first();

        if (!$user) {
            return back()->with('error', 'Data pengguna tidak ditemukan.')->with('showNewEmailModal', true);
        }

        // Cek password
        if (!Hash::check($request->currentPassword, $user->password)) {
            return back()->with('error', 'Kata sandi lama salah.')->with('showNewEmailModal', true);
        }

        // Cek apakah email baru sama dengan email sekarang
        if ($request->newEmail === $user->email) {
            return back()->with('error', 'Email baru tidak boleh sama dengan email saat ini.')->with('showNewEmailModal', true);
        }

        // Cek apakah email baru sudah digunakan user lain
        $emailExists = \App\Models\User::where('email', $request->newEmail)->where('uid', '!=', $user->uid)->exists();

        if ($emailExists) {
            return back()->with('error', 'Email tersebut sudah digunakan oleh pengguna lain.')->with('showNewEmailModal', true);
        }

        // Update email
        $user->email = $request->newEmail;
        $user->save();

        // Update session user juga
        session(['user' => $user]);

        return back()->with('success', 'Email Anda berhasil diperbarui.')->with('otpFor', 'newEmail');
    }

    public function updatePassword(Request $request)
    {
        // Validasi awal termasuk minimal karakter dan konfirmasi password
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required|min:6|confirmed',
        ], [
            'oldPassword.required' => 'Kata sandi lama wajib diisi.',
            'newPassword.required' => 'Kata sandi baru wajib diisi.',
            'newPassword.min' => 'Kata sandi baru minimal 6 karakter.',
            'newPassword.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('showPasswordModal', true);
        }

        // Ambil user dari session
        $user = session('user');
        if (!$user) {
            return back()->with('error', 'Pengguna tidak ditemukan.')->with('showPasswordModal', true);
        }

        $userModel = \App\Models\User::where('uid', $user->uid)->first();
        if (!$userModel) {
            return back()->with('error', 'Data pengguna tidak ditemukan.')->with('showPasswordModal', true);
        }

        // Verifikasi kata sandi lama
        if (!Hash::check($request->oldPassword, $userModel->password)) {
            return back()->with('error', 'Kata sandi lama salah.')->with('showPasswordModal', true);
        }

        // Cek apakah kata sandi baru sama dengan yang lama
        if (Hash::check($request->newPassword, $userModel->password)) {
            return back()->with('error', 'Kata sandi baru tidak boleh sama dengan kata sandi lama.')->with('showPasswordModal', true);
        }

        // Update password
        $userModel->password = Hash::make($request->newPassword);
        $userModel->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui.')->with('showPasswordSuccessModal', true);
    }

    private function sendOTP($email, $otp)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'devmossteam@gmail.com';
            $mail->Password = 'auarutsuzgpwtriy';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('devmossteam@gmail.com', 'Media Explant');
            $mail->addAddress($email);

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
                        .otp-box { background-color: #f3f3f3; border: 1px solid #dcdcdc; padding: 10px; text-align: center; font-size: 28px; font-weight: bold; margin: 20px 0; letter-spacing: 3px; }
                        .info { font-size: 16px; margin-top: 10px; }
                        .footer { text-align: center; font-size: 14px; color: #777; margin-top: 30px; }
                        .footer a { color: #ff4b5c; text-decoration: none; }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='header'>
                            <h2>Verifikasi Email Anda</h2>
                        </div>
                        <div class='content'>
                            <p>Halo,</p>
                            <p>Kami menerima permintaan untuk memverifikasi alamat email Anda di Media Explant.</p>
                            <p>Silakan gunakan kode OTP di bawah ini untuk melanjutkan proses verifikasi:</p>
                            <div class='otp-box'>{$otp}</div>
                            <p class='info'><strong>Email tujuan:</strong> {$email}</p>
                            <p class='info'><strong>Catatan:</strong> Kode OTP hanya berlaku selama 10 menit. Jangan bagikan kode ini kepada siapa pun.</p>
                        </div>
                        <div class='footer'>
                            <p>Salam hangat,<br>Tim Media Explant</p>
                        </div>
                    </div>
                </body>
                </html>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
