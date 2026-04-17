<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\API\Produk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProdukController extends Controller
{

    public function getDetailProduk(Request $request)
    {
        $produkId = $request->query('produk_id');
        $userId = $request->query('user_id') ?? null;

        if (!$produkId) {
            return response()->json(['message' => 'Parameter produk_id wajib diisi'], 400);
        }

        $produk = Produk::with(['user:uid,nama_lengkap', 'bookmarks', 'reaksis', 'komentars'])
            ->where('id', $produkId)
            ->where('visibilitas', 'public')
            ->first();

        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $tanggalDiterbitkan = Carbon::parse($produk->release_date);
        $detail = [
            'idproduk' => $produk->id,
            'judul' => $produk->judul,
            'cover' => $produk->cover,
            'deskripsi' => $produk->deskripsi,
            'release_date' => $tanggalDiterbitkan->toDateTimeString(),
            'penulis' => $produk->user->nama_lengkap ?? null,
            'kategori' => $produk->kategori,
            // 'media_url' => url('/api/produk-majalah/' . $produk->id . '/media'),
            'jumlahLike' => $produk->reaksis->where('jenis_reaksi', 'Suka')->count(),
            'jumlahDislike' => $produk->reaksis->where('jenis_reaksi', 'Tidak Suka')->count(),
            'jumlahKomentar' => $produk->komentars->count(),
            'isBookmark' => $produk->bookmarks->where('user_id', $userId)->count() > 0,
            'isLike' => $produk->reaksis->where('user_id', $userId)->where('jenis_reaksi', 'Suka')->count() > 0,
            'isDislike' => $produk->reaksis->where('user_id', $userId)->where('jenis_reaksi', 'Tidak Suka')->count() > 0,
        ];

        return response()->json($detail);
    }


    public function getProdukMajalah(Request $request)
    {
        $userId = $request->query('user_id') ?? null;
        $produk = Produk::select([
            'id',
            'user_id',
            'judul',
            'release_date',
            'kategori',
            'cover'
        ])
            ->where('kategori', 'Majalah')
            ->with(['user:uid,nama_lengkap', 'bookmarks', 'reaksis', 'komentars'])
            ->orderBy('release_date', 'desc')
            ->where('visibilitas', 'public')
            ->paginate(5);

        return response()->json($this->formatProdukResponse($produk, $userId));
    }

    public function getProdukBuletin(Request $request)
    {
        $userId = $request->query('user_id') ?? null;
        $produk = Produk::select([
            'id',
            'user_id',
            'judul',
            'release_date',
            'kategori',
            'cover'
        ])
            ->where('kategori', 'Buletin')
            ->with(['user:uid,nama_lengkap', 'bookmarks', 'reaksis', 'komentars'])
            ->orderBy('release_date', 'desc')
            ->where('visibilitas', 'public')
            ->paginate(5);

        return response()->json($this->formatProdukResponse($produk, $userId));
    }


    // Fungsi format data produk
    private function formatProdukResponse($produks, $userId)
    {
        return $produks->map(function ($produk) use ($userId) {
            $tanggalDiterbitkan = Carbon::parse($produk->release_date);
            return [
                'idproduk' => $produk->id,
                'judul' => $produk->judul,
                'cover' => $produk->cover,
                'release_date' => $tanggalDiterbitkan->toDateTimeString(),
                'kategori' => $produk->kategori,

            ];
        })->toArray();
    }

    public function getProdukMedia($id)
    {
        $produk = Produk::findOrFail($id);

        if (!$produk->media) {
            return response()->json(['message' => 'Media tidak ditemukan'], 404);
        }

        return response($produk->media)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="produk-' . $produk->id . '.pdf"')
            ->header('Accept-Ranges', 'bytes');
    }
}
