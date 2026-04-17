<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SignUpController extends Controller
{
    /**
     * Step 1: Validasi input (nama lengkap, username, email) & kirim OTP
     */
    public function registerStep1(Request $request)
    {
        // 1. Validasi input dasar
        $validator = Validator::make($request->all(), [
            'nama_lengkap'  => 'required|string|max:100',
            'nama_pengguna' => 'required|string|max:60',
            'email'         => 'required|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Cek duplikasi username atau email
        if (User::where('nama_pengguna', $request->nama_pengguna)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Username sudah digunakan.'
            ], 422);
        }
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah digunakan.'
            ], 422);
        }

        // 3. Generate OTP (6 digit)
        $otp = rand(100000, 999999);

        // 4. Simpan data pendaftaran sementara di cache (10 menit)
        $pendingData = [
            'nama_lengkap'  => $request->nama_lengkap,
            'nama_pengguna' => $request->nama_pengguna,
            'email'         => $request->email,
            'otp'           => $otp,
            'verified'      => false,
        ];
        Cache::put('pending_registration_' . $request->email, $pendingData, now()->addMinutes(10));

        // 5. Kirim OTP via PHPMailer dengan konfigurasi dari .env
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
            $mail->Port       = env('MAIL_PORT');

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($request->email, $request->nama_lengkap);
            $mail->Subject = 'Kode OTP Pendaftaran Anda';

            // --- Plain Text Version ---
            $plainText = "Halo {$request->nama_lengkap},\n\n" .
                         "Kode OTP untuk pendaftaran Anda adalah: {$otp}\n\n" .
                         "Kode ini hanya berlaku 10 menit.\n\n" .
                         "Jika bukan Anda yang meminta, abaikan pesan ini.\n\n" .
                         "Salam,\nTim Dukungan";

            // --- HTML Version ---
            $htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Pendaftaran</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f4f4f4;">
  <center style="width:100%;padding:20px 0;">
    <div style="max-width:600px;background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
      <div style="background:#E53935;padding:16px 24px;color:#FFFFFF;text-align:center;font-size:20px;font-weight:bold;">
        Kode OTP Pendaftaran
      </div>
      <div style="padding:24px;color:#333;line-height:1.6;font-size:16px;">
        <p>Halo <strong>{$request->nama_lengkap}</strong>,</p>
        <p>Terima kasih telah mendaftar. Berikut <strong>Kode OTP</strong> Anda:</p>
        <p style="font-size:28px;font-weight:bold;text-align:center;padding:12px 0;color:#E53935;">{$otp}</p>
        <p>Kode ini hanya berlaku selama <strong>10 menit</strong>. Jika Anda tidak meminta pendaftaran, silakan abaikan email ini.</p>
        <p>Salam,<br>Tim Dukungan</p>
      </div>
    </div>
  </center>
</body>
</html>
HTML;

            // Atur agar email mengirim HTML + plain text
            $mail->isHTML(true);
            $mail->Body    = $htmlBody;
            $mail->AltBody = $plainText;

            $mail->send();
        } catch (Exception $e) {
            // Jika pengiriman email gagal, hapus data pending
            Cache::forget('pending_registration_' . $request->email);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP: ' . $mail->ErrorInfo
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP telah dikirim ke email Anda. Silakan verifikasi OTP untuk melanjutkan.'
        ], 200);
    }

    /**
     * Step 2: Verifikasi OTP
     */
    public function verifyOtp(Request $request)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
            'otp'   => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Ambil data pending dari cache
        $pendingData = Cache::get('pending_registration_' . $request->email);

        if (!$pendingData) {
            return response()->json([
                'success' => false,
                'message' => 'Data pendaftaran tidak ditemukan atau telah kadaluarsa.'
            ], 404);
        }

        // 3. Cek OTP
        if ($pendingData['otp'] != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP yang dimasukkan salah.'
            ], 422);
        }

        // 4. Tandai sudah terverifikasi dan refresh cache
        $pendingData['verified'] = true;
        Cache::put('pending_registration_' . $request->email, $pendingData, now()->addMinutes(10));

        return response()->json([
            'success' => true,
            'message' => 'OTP berhasil diverifikasi.'
        ], 200);
    }

    /**
     * Step 3: Selesaikan Pendaftaran (input password) & masukkan ke database + kembalikan token
     */
    public function registerStep3(Request $request)
    {
        // 1. Validasi input password
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Ambil data pending dari cache
        $pendingData = Cache::get('pending_registration_' . $request->email);

        if (!$pendingData) {
            return response()->json([
                'success' => false,
                'message' => 'Data pendaftaran tidak ditemukan atau telah kadaluarsa.'
            ], 404);
        }

        // 3. Cek apakah sudah diverifikasi OTP
        if (!$pendingData['verified']) {
            return response()->json([
                'success' => false,
                'message' => 'OTP belum diverifikasi.'
            ], 422);
        }

        // 4. Generate UID
        $uuid  = Str::uuid()->toString();
        $parts = explode('-', $uuid);
        $uid   = $parts[0] . '-' . $parts[1] . '-' . $parts[2] . '-' . $parts[3] . '-' . substr($parts[4], 0, 4);

        // 5. Buat user baru di database
        $user = User::create([
            'uid'           => $uid,
            'nama_pengguna' => $pendingData['nama_pengguna'],
            'email'         => $pendingData['email'],
            'nama_lengkap'  => $pendingData['nama_lengkap'],
            'password'      => Hash::make($request->password),
            'role'          => 'Pembaca',
        ]);

        // 6. Hapus data pending dari cache
        Cache::forget('pending_registration_' . $request->email);

        // 7. Buat token API (Laravel Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 8. Kembalikan response sukses
        return response()->json([
            'success' => true,
            'message' => 'User berhasil didaftarkan!',
            'user'    => [
                'uid'           => $user->uid,
                'nama_pengguna' => $user->nama_pengguna,
                'email'         => $user->email,
                'role'          => $user->role,
            ],
            'token'   => $token,
        ], 201);
    }
}
