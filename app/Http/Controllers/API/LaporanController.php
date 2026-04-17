<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\API\Pesan;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id'      => 'nullable|exists:user,uid',
            'pesan'        => 'required|string',
            'status_read'  => 'required|in:sudah,belum',
            'status'       => 'required|in:laporan,masukan',
            'detail_pesan' => 'nullable|string',
            'pesan_type'   => 'required|string',
            'item_id'      => 'required|string',
        ]);

        $pesan = Pesan::create([
            'id'           => Str::random(12),
            'user_id'      => $request->user_id,
            'pesan'        => $request->pesan,
            'created_at'   => Carbon::now(),
            'status_read'  => $request->status_read,
            'status'       => $request->status,
            'detail_pesan' => $request->detail_pesan,
            'pesan_type'   => $request->pesan_type,
            'item_id'      => $request->item_id,
        ]);

        return response()->json([
            'message' => 'Laporan berhasil dikirim.',
            'data'    => $pesan
        ], 201);
    }
}
