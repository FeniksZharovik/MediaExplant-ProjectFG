<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('user-auth.login');
    }

    /**
     * Proses login pengguna.
     */
    public function login(Request $request)
    {
        // Validasi inputan
        $request->validate([
            'identifier' => 'required', // Bisa berupa nama_pengguna atau email
            'password' => 'required',
        ]);

        // Cari pengguna berdasarkan nama_pengguna atau email
        $user = User::where('nama_pengguna', $request->identifier)
            ->orWhere('email', $request->identifier)
            ->first();

        // Cek apakah pengguna ada dan password sesuai
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['message' => 'Nama pengguna atau password salah.']);
        }

        // Set cookie untuk menyimpan UID pengguna
        Cookie::queue('user_uid', $user->uid, 60 * 24 * 30); // Cookie disimpan selama 30 hari

        // Set data pengguna ke session untuk akses di view
        session(['user' => $user]);

        // Cek role pengguna dan arahkan ke halaman sesuai
        if ($user->role === 'Admin') {
            // Jika pengguna adalah Admin
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, Admin!');
        } elseif (in_array($user->role, ['Penulis', 'Pembaca'])) {
            // Jika pengguna adalah Penulis atau Pembaca
            return redirect('/')->with('success', 'Login berhasil!');
        } else {
            // Jika role tidak dikenali
            return back()->withErrors(['message' => 'Role pengguna tidak dikenali.']);
        }
    }

    /**
     * Proses logout pengguna.
     */
    public function logout()
    {
        // Hapus cookie saat pengguna logout
        Cookie::queue(Cookie::forget('user_uid'));

        // Hapus session pengguna
        session()->forget('user');

        // Redirect ke halaman login setelah logout
        return redirect()->route('login');
    }
}
