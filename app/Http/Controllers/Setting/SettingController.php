<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\User;

class SettingController extends Controller
{
    public function umumSettings()
    {
        $userUid = Cookie::get('user_uid');
        $user = $userUid
            ? User::where('uid', $userUid)->select('nama_pengguna', 'password', 'email', 'nama_lengkap', 'profile_pic')->first()
            : null;

        return view('settings.umum', ['user' => $user]);
    }

    public function uploadTempProfilePic(Request $request)
    {
        // Jika request mengandung penghapusan foto profil
        if ($request->has('delete_profile_pic')) {
            session(['temp_profile_pic' => null]);
            session(['delete_profile_pic' => true]);
            return redirect()->back();
        }

        // Validasi unggahan
        $request->validate([
            'profile_pic' => 'required|image|max:20480', // validasi gambar
        ]);

        $image = $request->file('profile_pic');
        $base64 = base64_encode(file_get_contents($image->getRealPath()));

        session(['temp_profile_pic' => $base64]);
        session()->forget('delete_profile_pic'); // batalkan jika sebelumnya berniat hapus

        return redirect()->back(); // kembali dengan session baru
    }

    public function saveProfile(Request $request)
    {
        $userUid = Cookie::get('user_uid');
        $user = $userUid ? User::where('uid', $userUid)->first() : null;

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $successMessages = [];

        // Update username
        if ($request->has('nama_pengguna') && $request->nama_pengguna !== $user->nama_pengguna) {
            $user->nama_pengguna = $request->nama_pengguna;
            $successMessages['success_nama_pengguna'] = 'Nama pengguna berhasil diperbarui.';
        }

        // Update full name
        if ($request->has('nama_lengkap') && $request->nama_lengkap !== $user->nama_lengkap) {
            $user->nama_lengkap = $request->nama_lengkap;
            $successMessages['success_nama_lengkap'] = 'Nama lengkap berhasil diperbarui.';
        }

        // Cek apakah user upload gambar baru (disimpan dalam session)
        if (session('temp_profile_pic')) {
            $user->profile_pic = base64_decode(session('temp_profile_pic'));
            $successMessages['success_profile_pic'] = 'Foto profil berhasil diperbarui.';
            session()->forget(['temp_profile_pic', 'delete_profile_pic']); // reset session setelah simpan
        }

        // Hapus foto profil jika diminta
        if (session('delete_profile_pic')) {
            $user->profile_pic = null;
            $successMessages['success_profile_pic_delete'] = 'Foto profil berhasil dihapus.';
            session()->forget('delete_profile_pic');
        }

        $user->save();

        return redirect()->back()->with($successMessages);
    }
}
