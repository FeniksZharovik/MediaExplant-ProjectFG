<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateProfileController extends Controller
{
    /**
     * POST /api/profile/update
     * – Update nama_pengguna & nama_lengkap
     * – Upload new profile_pic (file → Base64)
     * – Hapus foto lama jika flag delete_profile_pic = true
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([ 'success' => false, 'message' => 'User tidak terautentikasi.' ], 401);
        }

        // 1) Validasi input
        $data = $request->validate([
            'nama_pengguna'      => [
                'nullable','string','max:255',
                Rule::unique('user','nama_pengguna')->ignore($user->uid,'uid'),
            ],
            'nama_lengkap'       => 'nullable|string|max:255',
            'profile_pic'        => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delete_profile_pic' => 'nullable|boolean',
        ], [
            'nama_pengguna.unique' => 'Nama pengguna sudah dipakai.',
            'profile_pic.image'    => 'Profile picture harus gambar.',
            'profile_pic.mimes'    => 'Format: jpeg,png,jpg,gif,svg.',
            'profile_pic.max'      => 'Maksimal 2 MB.',
        ]);

        $messages = [];

        // 2) Hapus foto lama jika diminta
        if (! empty($data['delete_profile_pic'])) {
            $user->profile_pic = null;
            $messages[] = 'Foto profil dihapus.';
        }

        // 3) Update teks
        if (isset($data['nama_pengguna']) && $data['nama_pengguna'] !== $user->nama_pengguna) {
            $user->nama_pengguna = $data['nama_pengguna'];
            $messages[] = 'Nama pengguna diperbarui.';
        }
        if (isset($data['nama_lengkap']) && $data['nama_lengkap'] !== $user->nama_lengkap) {
            $user->nama_lengkap = $data['nama_lengkap'];
            $messages[] = 'Nama lengkap diperbarui.';
        }

        // 4) Upload & simpan BINARY blob, bukan string Data URI
        if ($request->hasFile('profile_pic')) {
            $file   = $request->file('profile_pic');
            $mime   = $file->getMimeType();               // ex: image/jpeg
            $binary = file_get_contents($file->getRealPath());
            $user->profile_pic = $binary;                 // simpan BLOB
            $messages[] = 'Foto profil diperbarui.';
        }

        // 5) Simpan perubahan
        $user->save();

        // 6) Siapkan Data URI untuk response
        $profilePicUri = null;
        if ($user->profile_pic) {
            // jika baru saja upload, kita sudah punya $mime dan $binary
            // tapi untuk konsistensi, decode dari DB:
            $binary = $user->profile_pic;
            // kalau tadi ada upload, override mime:
            if (isset($mime)) {
                $base64 = base64_encode($binary);
                $profilePicUri = "data:{$mime};base64,{$base64}";
            } else {
                // kalau tidak ada upload di request, coba deteksi tipe default:
                $profilePicUri = 'data:image/jpeg;base64,'.base64_encode($binary);
            }
        }

        return response()->json([
            'success'       => true,
            'message'       => 'Profil berhasil diperbarui!',
            'notifications' => $messages,
            'user'          => [
                'uid'           => $user->uid,
                'nama_pengguna' => $user->nama_pengguna,
                'nama_lengkap'  => $user->nama_lengkap,
                'email'         => $user->email,
                'profile_pic'   => $profilePicUri,
                'role'          => $user->role,
            ],
        ], 200);
    }

    // ––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––

    /**
     * GET /api/profile/check-username?nama_pengguna=...
     */
    public function checkUsername(Request $request)
    {
        $user = $request->user();
        $username = $request->query('nama_pengguna');

        if (! $username) {
            return response()->json([
                'available' => false,
                'message'   => 'Parameter nama_pengguna diperlukan.',
            ], 400);
        }

        $validator = \Validator::make(
            ['nama_pengguna' => $username],
            ['nama_pengguna' => [
                'required','string','max:255',
                Rule::unique('user','nama_pengguna')->ignore($user->uid,'uid'),
            ]]
        );

        if ($validator->fails()) {
            return response()->json([
                'available' => false,
                'message'   => 'Nama pengguna sudah dipakai.',
            ], 200);
        }

        return response()->json([
            'available' => true,
            'message'   => 'Nama pengguna tersedia.',
        ], 200);
    }

    /**
     * POST /api/profile/delete-image
     */
    public function deleteProfileImage(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi.',
            ], 401);
        }

        if (empty($user->profile_pic)) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada foto untuk dihapus.',
                'user'    => ['uid' => $user->uid, 'profile_pic' => null],
            ], 200);
        }

        $user->profile_pic = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus.',
            'user'    => ['uid' => $user->uid, 'profile_pic' => null],
        ], 200);
    }
}
