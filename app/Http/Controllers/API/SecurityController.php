<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Exception;
use Carbon\Carbon;

class SecurityController extends Controller
{
    /**
     * Ubah password setelah memverifikasi current_password.
     * POST /password/change
     */
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $v = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|different:current_password',
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $v->errors(),
            ], 422);
        }

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini tidak cocok.',
            ], 403);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        // Kirim email notifikasi perubahan password (HTML + Plain Text)
        try {
            $when  = Carbon::now()->format('Y-m-d H:i:s');
            $greet = property_exists($user, 'nama_pengguna') ? $user->nama_pengguna : $user->email;

            // Plain Text
            $plainText = "Halo {$greet},\n\n" .
                         "Password akun Anda telah berhasil diubah pada {$when}.\n\n" .
                         "Jika ini bukan Anda, segera hubungi tim dukungan untuk mengamankan akun Anda.\n\n" .
                         "Salam,\nTim MediaExplant";

            // HTML Body
            $htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifikasi Ganti Password</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f4f4f4;">
  <center style="width:100%;padding:20px 0;">
    <div style="max-width:600px;background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
      <div style="background:#1976D2;padding:16px 24px;color:#FFFFFF;text-align:center;font-size:20px;font-weight:bold;">
        Password Berhasil Diubah
      </div>
      <div style="padding:24px;color:#333;line-height:1.6;font-size:16px;">
        <p>Halo <strong>{$greet}</strong>,</p>
        <p>Password akun Anda telah <strong>berhasil diubah</strong> pada <strong>{$when}</strong>.</p>
        <p>Jika ini bukan Anda, <strong>segera hubungi tim dukungan</strong> untuk mengamankan akun Anda.</p>
        <p>Salam,<br>Tim MediaExplant</p>
      </div>
    </div>
  </center>
</body>
</html>
HTML;

            Mail::send([], [], function ($message) use ($user, $plainText, $htmlBody) {
                $message->to($user->email)
                        ->subject('Notifikasi: Password Berhasil Diubah');

                // ✏️ Ganti setBody/addPart menjadi html() + text()
                $message->html($htmlBody);
                $message->text($plainText);
            });
        } catch (Exception $e) {
            Log::warning('Gagal mengirim email notifikasi ubah password: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah!',
        ]);
    }

    //───────────────────────────────────────────────────
    // STEP 1: Verifikasi email lama sebelum ganti email
    //───────────────────────────────────────────────────

    /**
     * Kirim OTP ke email lama user.
     * POST /email/send-change-otp
     */
    public function sendChangeEmailOtp(Request $request)
    {
        $user = $request->user();
        $otp  = random_int(100000, 999999);
        Cache::put("old_email_otp_{$user->uid}", $otp, now()->addMinutes(15));

        try {
            // Plain Text
            $plainText = "Halo {$user->nama_pengguna},\n\n" .
                         "Kode OTP verifikasi email lama Anda adalah: {$otp}\n\n" .
                         "Kode ini berlaku 15 menit.\n\n" .
                         "Jika Anda tidak meminta, abaikan email ini.\n\n" .
                         "Salam,\nTim MediaExplant";

            // HTML Body
            $htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Verifikasi Email Lama</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f4f4f4;">
  <center style="width:100%;padding:20px 0;">
    <div style="max-width:600px;background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
      <div style="background:#0288D1;padding:16px 24px;color:#FFFFFF;text-align:center;font-size:20px;font-weight:bold;">
        OTP Verifikasi Email Lama
      </div>
      <div style="padding:24px;color:#333;line-height:1.6;font-size:16px;">
        <p>Halo <strong>{$user->nama_pengguna}</strong>,</p>
        <p>Berikut <strong>Kode OTP</strong> untuk memverifikasi email lama Anda:</p>
        <p style="font-size:28px;font-weight:bold;text-align:center;padding:12px 0;color:#0288D1;">{$otp}</p>
        <p>Kode ini <strong>berlaku 15 menit</strong>. Jika Anda tidak meminta, silakan abaikan pesan ini.</p>
        <p>Salam,<br>Tim MediaExplant</p>
      </div>
    </div>
  </center>
</body>
</html>
HTML;

            Mail::send([], [], function ($message) use ($user, $plainText, $htmlBody) {
                $message->to($user->email)
                        ->subject('OTP Verifikasi Email Lama');

                // ✏️ Ganti setBody/addPart menjadi html() + text()
                $message->html($htmlBody);
                $message->text($plainText);
            });

            return response()->json([
                'success' => true,
                'message' => 'OTP telah dikirim ke email lama Anda.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifikasi OTP email lama.
     * POST /email/verify-old-email-otp
     */
    public function verifyOldEmailOtp(Request $request)
    {
        $user = $request->user();
        $v = Validator::make($request->all(), [
            'otp' => 'required|string',
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $v->errors()
            ], 422);
        }

        $cacheKey = "old_email_otp_{$user->uid}";
        if (! Cache::has($cacheKey) || Cache::get($cacheKey) != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid atau sudah kedaluwarsa.'
            ], 400);
        }

        Cache::forget($cacheKey);

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi email lama berhasil.'
        ]);
    }

    //───────────────────────────────────────────────────
    // STEP 2: Input & verifikasi email baru
    //───────────────────────────────────────────────────

    /**
     * Terima new_email, kirim OTP ke alamat baru.
     * POST /email/send-new-email-otp
     */
    public function sendNewEmailOtp(Request $request)
    {
        $user = $request->user();
        $v = Validator::make($request->all(), [
            'new_email' => 'required|email|unique:user,email',
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $v->errors()
            ], 422);
        }

        $newEmail = $request->new_email;
        $otp      = random_int(100000, 999999);

        Cache::put("new_email_pending_{$user->uid}", $newEmail, now()->addMinutes(15));
        Cache::put("new_email_otp_{$user->uid}", $otp, now()->addMinutes(15));

        try {
            // Plain Text
            $plainText = "Halo {$user->nama_pengguna},\n\n" .
                         "Kode OTP untuk verifikasi email baru Anda ({$newEmail}) adalah: {$otp}\n\n" .
                         "Kode ini berlaku 15 menit.\n\n" .
                         "Jika Anda tidak meminta, abaikan email ini.\n\n" .
                         "Salam,\nTim MediaExplant";

            // HTML Body
            $htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Verifikasi Email Baru</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f4f4f4;">
  <center style="width:100%;padding:20px 0;">
    <div style="max-width:600px;background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
      <div style="background:#00796B;padding:16px 24px;color:#FFFFFF;text-align:center;font-size:20px;font-weight:bold;">
        OTP Verifikasi Email Baru
      </div>
      <div style="padding:24px;color:#333;line-height:1.6;font-size:16px;">
        <p>Halo <strong>{$user->nama_pengguna}</strong>,</p>
        <p>Anda ingin mengganti email ke: <strong>{$newEmail}</strong></p>
        <p>Berikut <strong>Kode OTP</strong> untuk memverifikasi email baru tersebut:</p>
        <p style="font-size:28px;font-weight:bold;text-align:center;padding:12px 0;color:#00796B;">{$otp}</p>
        <p>Kode ini <strong>berlaku 15 menit</strong>. Jika Anda tidak meminta perubahan email, silakan abaikan pesan ini.</p>
        <p>Salam,<br>Tim MediaExplant</p>
      </div>
    </div>
  </center>
</body>
</html>
HTML;

            Mail::send([], [], function ($message) use ($newEmail, $plainText, $htmlBody) {
                $message->to($newEmail)
                        ->subject('OTP Verifikasi Email Baru');

                // ✏️ Ganti setBody/addPart menjadi html() + text()
                $message->html($htmlBody);
                $message->text($plainText);
            });

            return response()->json([
                'success' => true,
                'message' => 'OTP telah dikirim ke email baru.'
            ]);
        } catch (Exception $e) {
            // rollback cache jika gagal
            Cache::forget("new_email_pending_{$user->uid}");
            Cache::forget("new_email_otp_{$user->uid}");
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP ke email baru.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifikasi OTP & simpan email baru.
     * POST /email/verify-new-email-otp
     */
    public function verifyNewEmailOtp(Request $request)
    {
        $user = $request->user();
        $v = Validator::make($request->all(), [
            'otp' => 'required|string',
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $v->errors()
            ], 422);
        }

        $otpKey   = "new_email_otp_{$user->uid}";
        $emailKey = "new_email_pending_{$user->uid}";

        if (! Cache::has($otpKey) || Cache::get($otpKey) != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid atau sudah kedaluwarsa.'
            ], 400);
        }

        $newEmail = Cache::get($emailKey);
        if (empty($newEmail)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ditemukan email baru yang dipending.'
            ], 400);
        }

        // Update email & tandai terverifikasi
        $user->email = $newEmail;
        // $user->email_verified_at = Carbon::now();
        $user->save();

        Cache::forget($otpKey);
        Cache::forget($emailKey);

        return response()->json([
            'success' => true,
            'message' => 'Email baru berhasil diverifikasi dan disimpan.'
        ]);
    }

    //───────────────────────────────────────────────────
    // LUPA PASSWORD
    //───────────────────────────────────────────────────

    /**
     * Kirim OTP ke email untuk reset password.
     * POST /password/send-reset-otp
     */
    public function sendResetPasswordOtp(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email|exists:user,email',
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $v->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $otp  = random_int(100000, 999999);
        Cache::put("reset_password_otp_{$user->uid}", $otp, now()->addMinutes(15));

        try {
            // Plain Text
            $plainText = "Halo {$user->nama_pengguna},\n\n" .
                         "Kode OTP untuk reset password Anda adalah: {$otp}\n\n" .
                         "Kode ini berlaku 15 menit.\n\n" .
                         "Jika Anda tidak meminta, abaikan email ini.\n\n" .
                         "Salam,\nTim MediaExplant";

            // HTML Body
            $htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Reset Password</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f4f4f4;">
  <center style="width:100%;padding:20px 0;">
    <div style="max-width:600px;background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
      <div style="background:#C62828;padding:16px 24px;color:#FFFFFF;text-align:center;font-size:20px;font-weight:bold;">
        OTP Reset Password
      </div>
      <div style="padding:24px;color:#333;line-height:1.6;font-size:16px;">
        <p>Halo <strong>{$user->nama_pengguna}</strong>,</p>
        <p>Berikut <strong>Kode OTP</strong> untuk mereset password Anda:</p>
        <p style="font-size:28px;font-weight:bold;text-align:center;padding:12px 0;color:#C62828;">{$otp}</p>
        <p>Kode ini <strong>berlaku 15 menit</strong>. Jika Anda tidak meminta reset password, silakan abaikan pesan ini.</p>
        <p>Salam,<br>Tim MediaExplant</p>
      </div>
    </div>
  </center>
</body>
</html>
HTML;

            Mail::send([], [], function ($message) use ($user, $plainText, $htmlBody) {
                $message->to($user->email)
                        ->subject('OTP Reset Password');

                // ✏️ Ganti setBody/addPart menjadi html() + text()
                $message->html($htmlBody);
                $message->text($plainText);
            });

            return response()->json([
                'success' => true,
                'message' => 'OTP telah dikirim ke email Anda.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim OTP.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifikasi OTP reset password.
     * POST /password/verify-reset-otp
     */
    public function verifyResetPasswordOtp(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email|exists:user,email',
            'otp'   => 'required|string',
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $user      = User::where('email', $request->email)->firstOrFail();
        $cacheKey  = "reset_password_otp_{$user->uid}";
        $cachedOtp = Cache::get($cacheKey);

        if (! $cachedOtp || $cachedOtp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid atau sudah kedaluwarsa.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP reset password valid.'
        ]);
    }

    /**
     * Reset password setelah verifikasi OTP.
     * POST /password/reset
     */
    public function resetPassword(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email'                     => 'required|email|exists:user,email',
            'otp'                       => 'required|string',
            'new_password'              => 'required|string|min:6|confirmed',
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $user      = User::where('email', $request->email)->firstOrFail();
        $cacheKey  = "reset_password_otp_{$user->uid}";
        $cachedOtp = Cache::get($cacheKey);

        if (! $cachedOtp || $cachedOtp != $request->otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid atau sudah kedaluwarsa.'
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();
        Cache::forget($cacheKey);

        // Kirim email notifikasi reset password (HTML + Plain Text)
        try {
            $when  = Carbon::now()->format('Y-m-d H:i:s');
            $greet = property_exists($user, 'nama_pengguna') ? $user->nama_pengguna : $user->email;

            // Plain Text
            $plainText = "Halo {$greet},\n\n" .
                         "Password akun Anda telah berhasil direset pada {$when}.\n\n" .
                         "Jika ini bukan Anda, segera hubungi tim dukungan untuk mengamankan akun Anda.\n\n" .
                         "Salam,\nTim MediaExplant";

            // HTML Body
            $htmlBody = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifikasi Reset Password</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f4f4f4;">
  <center style="width:100%;padding:20px 0;">
    <div style="max-width:600px;background:#FFFFFF;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
      <div style="background:#D32F2F;padding:16px 24px;color:#FFFFFF;text-align:center;font-size:20px;font-weight:bold;">
        Password Berhasil Direset
      </div>
      <div style="padding:24px;color:#333;line-height:1.6;font-size:16px;">
        <p>Halo <strong>{$greet}</strong>,</p>
        <p>Password akun Anda telah <strong>berhasil direset</strong> pada <strong>{$when}</strong>.</p>
        <p>Jika ini bukan Anda, <strong>segera hubungi tim dukungan</strong> untuk mengamankan akun Anda.</p>
        <p>Salam,<br>Tim MediaExplant</p>
      </div>
    </div>
  </center>
</body>
</html>
HTML;

            Mail::send([], [], function ($message) use ($user, $plainText, $htmlBody) {
                $message->to($user->email)
                        ->subject('Notifikasi: Password Berhasil Direset');

                // ✏️ Ganti setBody/addPart menjadi html() + text()
                $message->html($htmlBody);
                $message->text($plainText);
            });
        } catch (Exception $e) {
            Log::warning('Gagal mengirim email notifikasi reset password: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset.'
        ]);
    }
}
