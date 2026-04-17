<?php

namespace App\Http\Controllers\UserReact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserReact\Komentar;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class KomentarController extends Controller
{
    public function __construct()
    {
        Carbon::setLocale('id');  // Set locale Carbon ke bahasa Indonesia
        setlocale(LC_TIME, 'id_ID.UTF-8'); // untuk fungsi strftime jika perlu
    }

    // Fungsi bantu buat format tanggal komentar secara natural (baru saja, menit lalu, jam lalu, dll)
    private function waktuIndo(Carbon $waktu)
    {
        $now = Carbon::now();
        $diff = $now->diffInSeconds($waktu);

        if ($diff < 60) {
            return 'baru saja';
        } elseif ($diff < 3600) {
            $menit = $now->diffInMinutes($waktu);
            return $menit . ' menit lalu';
        } elseif ($diff < 86400) {
            $jam = $now->diffInHours($waktu);
            return $jam . ' jam lalu';
        } elseif ($diff < 604800) {
            $hari = $now->diffInDays($waktu);
            return $hari . ' hari lalu';
        } elseif ($diff < 2419200) {
            $minggu = floor($now->diffInDays($waktu) / 7);
            return $minggu . ' minggu lalu';
        } else {
            return $waktu->translatedFormat('d F Y');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'komentar' => 'required|string|max:1000',
            'item_id' => 'required|string',
            'komentar_type' => 'required|string', // Pastikan 'Produk' digunakan di sini
            'parent_id' => 'nullable|string'
        ]);

        $user_uid = Cookie::get('user_uid');
        if (!$user_uid) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $user = User::where('uid', $user_uid)->first();
        if (!$user) return response()->json(['success' => false, 'message' => 'User not found'], 401);

        // Simpan komentar
        $komentar = Komentar::create([
            'id' => Str::random(12),
            'user_id' => $user->uid,
            'isi_komentar' => $request->komentar,
            'tanggal_komentar' => Carbon::now(),
            'komentar_type' => $request->komentar_type,
            'item_id' => $request->item_id,
            'parent_id' => $request->parent_id ?? null
        ]);

        return response()->json([
            'success' => true,
            'isi_komentar' => $komentar->isi_komentar,
            'nama_pengguna' => $user->nama_pengguna,
            'profile_pic' => $user->profile_pic ? 'data:image/jpeg;base64,' . base64_encode($user->profile_pic) : null,
            'parent_id' => $komentar->parent_id,
            'id' => $komentar->id
        ]);
    }

    public function fetch($item_id, $komentar_type)
    {
        $komentar = Komentar::with('user')
            ->where('item_id', $item_id)
            ->where('komentar_type', $komentar_type)
            ->latest('tanggal_komentar')
            ->get();

        return response()->json($komentar);
    }

    public function fetchReplies($komentarId)
    {
        $komentar = Komentar::with(['replies.user', 'replies.replies.user'])->findOrFail($komentarId);

        return view('user-react.partials.replies-komentar', [
            'komentar' => $komentar
        ]);
    }

    public function destroy($id)
    {
        $user_uid = Cookie::get('user_uid');
        if (!$user_uid) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $komentar = Komentar::where('id', $id)
            ->where('user_id', $user_uid)
            ->first();

        if (!$komentar) {
            return response()->json(['success' => false, 'message' => 'Komentar tidak ditemukan atau tidak diizinkan'], 404);
        }

        $komentar->deleteWithReplies();

        return response()->json(['success' => true]);
    }
}
