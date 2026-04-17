<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|string|exists:user,uid',
            'item_id'   => 'required|string',
            'bookmark_type' => 'required|string',
        ]);

        $bookmark = Bookmark::where('user_id', $request->user_id)
                            ->where('item_id', $request->item_id)
                            ->where('bookmark_type', $request->bookmark_type)
                            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json([
                'status'  => 'removed',
                'message' => 'Bookmark dihapus.',
            ]);
        } else {
            $newBookmark = Bookmark::create($request->only('user_id', 'item_id', 'bookmark_type'));

            return response()->json([
                'status'  => 'added',
                'message' => 'Bookmark ditambahkan.',
                'data'    => $newBookmark,
            ]);
        }
    }
}


