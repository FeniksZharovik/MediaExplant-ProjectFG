<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User; 

class ChangePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        // Periksa apakah email pengguna tersedia di session
        $email = Session::get('otp_email');
        if (!$email) {
            return redirect()->route('password.forgotPassword')->withErrors(['error' => 'Sesi Anda telah berakhir. Silakan coba lagi.']);
        }

        return view('user-auth.change_password', ['email' => $email]);
    }

    public function changePassword(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Ambil email dari session
        $email = Session::get('otp_email');
        if (!$email) {
            return redirect()->route('password.forgotPassword')->withErrors(['error' => 'Sesi Anda telah berakhir. Silakan coba lagi.']);
        }

        // Cari pengguna berdasarkan email
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.forgotPassword')->withErrors(['error' => 'Pengguna tidak ditemukan.']);
        }

        // Update password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Hapus session OTP
        Session::forget(['otp', 'otp_email', 'otp_expiration']);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login.');
    }
}
