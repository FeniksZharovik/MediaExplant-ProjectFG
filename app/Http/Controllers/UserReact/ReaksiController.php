<?php

namespace App\Http\Controllers\UserReact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\UserReact\Reaksi;

class ReaksiController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'item_id' => 'required|string',
                'jenis_reaksi' => 'required|in:Suka,Tidak Suka',
                'reaksi_type' => 'required|string'
            ]);

            // Ambil UID dari session atau cookie
            $userUid = session('user.uid') ?? Cookie::get('user_uid');

            if (!$userUid) {
                return response()->json([
                    'message' => 'User belum login'
                ], 401);
            }

            // Cek apakah user sudah memberi reaksi pada item ini
            $reaksi = Reaksi::where('user_id', $userUid)
                ->where('item_id', $request->item_id)
                ->where('reaksi_type', $request->reaksi_type)
                ->first();

            if ($reaksi) {
                if ($reaksi->jenis_reaksi === $request->jenis_reaksi) {
                    // Jika klik reaksi yang sama → batalkan
                    $reaksi->delete();
                } else {
                    // Jika beda jenis → update jenis_reaksi
                    $reaksi->update([
                        'jenis_reaksi' => $request->jenis_reaksi,
                        'tanggal_reaksi' => now()
                    ]);
                }
            } else {
                // Belum ada reaksi → buat baru
                Reaksi::create([
                    'id' => Str::random(12),
                    'user_id' => $userUid,
                    'item_id' => $request->item_id,
                    'reaksi_type' => $request->reaksi_type,
                    'jenis_reaksi' => $request->jenis_reaksi,
                    'tanggal_reaksi' => now()
                ]);
            }

            // Hitung total reaksi
            $likeCount = Reaksi::where('item_id', $request->item_id)
                ->where('jenis_reaksi', 'Suka')
                ->count();

            $dislikeCount = Reaksi::where('item_id', $request->item_id)
                ->where('jenis_reaksi', 'Tidak Suka')
                ->count();

            return response()->json([
                'message' => 'Reaksi berhasil diproses',
                'likeCount' => $likeCount,
                'dislikeCount' => $dislikeCount
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan reaksi: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }
}
