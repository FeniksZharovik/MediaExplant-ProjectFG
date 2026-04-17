<?php

namespace App\Http\Controllers\UserReact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class BookmarkController extends Controller
{
    /**
     * Toggle bookmark (simpan atau hapus) untuk berita oleh user.
     */
    public function toggle(Request $request)
    {
        $userUid = Cookie::get('user_uid');

        if (!$userUid) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $user = User::where('uid', $userUid)->first();

        if (!$user) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $request->validate([
            'item_id' => 'required|string|size:12',
            'bookmark_type' => 'required|in:Berita,Produk,Karya'
        ]);

        $itemId = $request->item_id;
        $bookmarkType = $request->bookmark_type;

        $existing = DB::table('bookmark')
            ->where('user_id', $user->uid)
            ->where('item_id', $itemId)
            ->where('bookmark_type', $bookmarkType)
            ->first();

        if ($existing) {
            DB::table('bookmark')->where('id', $existing->id)->delete();
            return response()->json(['status' => 'unbookmarked']);
        } else {
            DB::table('bookmark')->insert([
                'id' => Str::random(12),
                'user_id' => $user->uid,
                'tanggal_bookmark' => now(),
                'bookmark_type' => $bookmarkType,
                'item_id' => $itemId,
            ]);
            return response()->json(['status' => 'bookmarked']);
        }
    }
}
