<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\API\Berita;
use App\Models\API\Karya;
use App\Models\API\Produk;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function searchAll(Request $request)
    {
        $keyword = $request->query('katakunci');

        if (!$keyword) {
            return response()->json(['message' => 'Parameter katakunci wajib diisi'], 400);
        }

        $berita = Berita::select('id', 'judul', 'tanggal_diterbitkan as tanggal', 'kategori')
            ->where('visibilitas', 'public')
            ->where(function ($query) use ($keyword) {
                $query->where('judul', 'like', "%$keyword%")
                    ->orWhere('kategori', 'like', "%$keyword%");
            })
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'berita',
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'tanggal' => Carbon::parse($item->tanggal)->toDateTimeString(),
                    'kategori' => $item->kategori,
                ];
            });
        // Pastikan dikonversi ke collection Laravel
        $berita = collect($berita);

        $karya = Karya::select('id', 'judul', 'release_date as tanggal', 'kategori')
            ->where('visibilitas', 'public')
            ->where(function ($query) use ($keyword) {
                $query->where('judul', 'like', "%$keyword%")
                    ->orWhere('deskripsi', 'like', "%$keyword%")
                    ->orWhere('kategori', 'like', "%$keyword%");
            })
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'karya',
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'tanggal' => Carbon::parse($item->tanggal)->toDateTimeString(),
                    'kategori' => $item->kategori,
                ];
            });
        $karya = collect($karya);

        $produk = Produk::select('id', 'judul', 'release_date as tanggal', 'kategori')
            ->where('visibilitas', 'public')
            ->where(function ($query) use ($keyword) {
                $query->where('judul', 'like', "%$keyword%")
                    ->orWhere('deskripsi', 'like', "%$keyword%")
                    ->orWhere('kategori', 'like', "%$keyword%");
            })
            ->get()
            ->map(function ($item) {
                return [
                    'tipe' => 'produk',
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'tanggal' => Carbon::parse($item->tanggal)->toDateTimeString(),
                    'kategori' => $item->kategori,
                ];
            });
        $produk = collect($produk);

        // Merge ketiga collection yang sudah pasti collection Laravel
        $hasil = $berita->merge($karya)->merge($produk)->sortByDesc('tanggal')->values();

        return response()->json($hasil);
    }
}
