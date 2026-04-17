<?php

namespace App\Http\Controllers\UserReact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use App\Models\UserReact\Report;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $userUid = Cookie::get('user_uid');

        if (!$userUid) {
            return response()->json(['success' => false, 'message' => 'Anda harus login untuk melaporkan.'], 401);
        }

        $user = User::where('uid', $userUid)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 401);
        }

        $request->validate([
            'report_reason' => 'required|string|max:255',
            'detail_pesan' => 'nullable|string|max:500',
            'item_id' => 'required|string|size:12'
        ]);

        Report::create([
            'user_id'      => $user->uid,
            'pesan'        => $request->report_reason,
            'detail_pesan' => $request->detail_pesan,
            'status_read'  => 'belum',
            'status'       => 'laporan',
            'pesan_type'   => $request->input('pesan_type', 'Berita'),
            'item_id'      => $request->item_id
        ]);

        return response()->json(['success' => true, 'message' => 'Laporan berhasil dikirim.']);
    }
}
