<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\Reaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReaksiController extends Controller
{
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'item_id' => 'required|string',
            'reaksi_type' => 'required|string',
            'jenis_reaksi' => 'required|in:Suka,Tidak Suka',
        ]);
    
        $reaksi = Reaksi::where('user_id', $validated['user_id'])
            ->where('item_id', $validated['item_id'])
            ->where('reaksi_type', $validated['reaksi_type'])
            ->first();
    
        if ($reaksi) {
            // Jika jenis reaksi sama, maka hapus
            if ($reaksi->jenis_reaksi === $validated['jenis_reaksi']) {
                $reaksi->delete();
                return response()->json(['message' => 'Reaksi dihapus']);
            } else {
                // Jika jenis reaksi beda, update
                $reaksi->jenis_reaksi = $validated['jenis_reaksi'];
                $reaksi->tanggal_reaksi = now();
                $reaksi->save();
                return response()->json(['message' => 'Reaksi diperbarui']);
            }
        }
    
        // Jika belum ada, buat baru
        Reaksi::create([
            'user_id' => $validated['user_id'],
            'item_id' => $validated['item_id'],
            'reaksi_type' => $validated['reaksi_type'],
            'jenis_reaksi' => $validated['jenis_reaksi'],
            'tanggal_reaksi' => now(),
        ]);
    
        return response()->json(['message' => 'Reaksi ditambahkan']);
    }
}
