<?php

namespace App\Http\Controllers\API;

use App\Models\API\Berita;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class BeritaController extends Controller
{
    // Fungsi bantu ambil limit & page dari query parameter
    private function getPaginationParams(Request $request)
    {
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        return [(int) $limit, (int) $page];
    }

    // Format response berita dengan tambahan data reaksi, komentar, bookmark, dll
    private function formatBeritaResponse($beritas, $userId)
    {
        return $beritas->map(function ($berita) use ($userId) {
            $tanggalDiterbitkan = Carbon::parse($berita->tanggal_diterbitkan);
            return [
                'idBerita' => $berita->id,
                'judul' => $berita->judul,
                'kontenBerita' => $berita->konten_berita,
                'tanggalDibuat' => $tanggalDiterbitkan->toDateTimeString(),
                'kategori' => $berita->kategori,

            ];
        });
    }

    // Ambil detail berita berdasarkan ID
    public function getDetailBerita(Request $request)
    {
        $id = $request->query('id');
        $userId = $request->query('user_id');
        $berita = Berita::with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
            ->where('id', $id)
            ->where('visibilitas', 'public')
            ->first();
        if (!$berita) {
            return response()->json(['message' => 'Berita tidak ditemukan'], 404);
        }

        $tanggalDiterbitkan = Carbon::parse($berita->tanggal_diterbitkan);

        return response()->json([
            'data' => [
                'idBerita' => $berita->id,
                'judul' => $berita->judul,
                'kontenBerita' => $berita->konten_berita,
                'tanggalDibuat' => $tanggalDiterbitkan->toDateTimeString(),
                'penulis' => $berita->user->nama_lengkap ?? null,
                'kategori' => $berita->kategori,
                'jumlahLike' => $berita->reaksis->where('jenis_reaksi', 'Suka')->count(),
                'jumlahDislike' => $berita->reaksis->where('jenis_reaksi', 'Tidak Suka')->count(),
                'jumlahKomentar' => $berita->komentars->count(),
                'tags' => $berita->tags->pluck('nama_tag'),
                'isBookmark' => $berita->bookmarks->where('user_id', $userId)->count() > 0,
                'isLike' => $berita->reaksis->where('user_id', $userId)->where('jenis_reaksi', 'Suka')->count() > 0,
                'isDislike' => $berita->reaksis->where('user_id', $userId)->where('jenis_reaksi', 'Tidak Suka')->count() > 0,
            ]
        ]);
    }


    // Ambil berita terbaru dengan pagination
    public function getBeritaTerbaru(Request $request)
    {
        $userId = $request->query('user_id');
        [$limit, $page] = $this->getPaginationParams($request);
        $beritas = Berita::with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
            ->where('visibilitas', 'public')
            ->orderByDesc('tanggal_diterbitkan')
            ->paginate($limit, ['*'], 'page', $page);
        return response()->json([
            'data' => $this->formatBeritaResponse($beritas, $userId)
        ]);
    }

    // Ambil berita populer teratas berdasarkan jumlah like dan view count
    public function getBeritaPopuler(Request $request)
    {
        $userId = $request->query('user_id');

        $beritas = Berita::withCount([
            'reaksis as jumlah_like' => function ($q) {
                $q->where('jenis_reaksi', 'Suka');
            },
            'komentars as jumlah_komentar'
        ])
            ->with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
            ->where('visibilitas', 'public')
            ->selectRaw('berita.*, 
            (view_count + 
             (SELECT COUNT(*) FROM reaksi WHERE reaksi.jenis_reaksi = "Suka" AND reaksi.item_id = berita.id AND reaksi.reaksi_type = "Berita") * 2 + 
             (SELECT COUNT(*) FROM komentar WHERE komentar.item_id = berita.id AND komentar.komentar_type = "Berita") * 3) as skor')
            ->orderByDesc('skor')
            ->limit(5)
            ->get();

        return response()->json([
            'data' => $this->formatBeritaResponse($beritas, $userId)
        ]);
    }


    // Ambil berita teratas (top 1 berdasarkan view count dan like)
    public function getBeritaTeratas(Request $request)
    {
        $userId = $request->query('user_id');

        $beritas = Berita::withCount(['reaksis as jumlah_like' => function ($q) {
            $q->where('jenis_reaksi', 'Suka');
        }])
            ->with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
            ->where('visibilitas', 'public')
            ->orderByDesc('view_count')
            ->orderByDesc('jumlah_like')
            ->limit(1)
            ->get();

        return response()->json([
            'data' => $this->formatBeritaResponse($beritas, $userId)
        ]);
    }

    // Ambil berita terkait berdasarkan kategori dan berita id
    public function getBeritaTerkait(Request $request)
    {
        $userId = $request->query('user_id');
        $beritaId = $request->query('berita_id');
        $kategori = $request->query('kategori');
        [$limit, $page] = $this->getPaginationParams($request);

        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 400);
        }

        $beritaUtama = Berita::find($beritaId);
        if (!$beritaUtama) {
            return response()->json(['message' => 'Berita tidak ditemukan'], 404);
        }

        $beritas = Berita::with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
            ->where('id', '!=', $beritaId)
            ->where('kategori', $kategori)
            ->where('visibilitas', 'public')
            ->orderByDesc('tanggal_diterbitkan')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $this->formatBeritaResponse($beritas, $userId)
        ]);
    }

    // Ambil berita topik lainnya (selain kategori berita di detail), urutkan berdasarkan algoritma: view_count * 1 + like * 2
    public function getBeritaTopikLainnya(Request $request)
    {
        $userId = $request->query('user_id');
        $kategori = $request->query('kategori');
        $beritaId = $request->query('berita_id');
        [$limit, $page] = $this->getPaginationParams($request);

        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 400);
        }

        $beritas = Berita::withCount([
            'reaksis as jumlah_like' => function ($q) {
                $q->where('jenis_reaksi', 'Suka');
            }
        ])
            ->with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
            ->where('visibilitas', 'public')
            ->where('id', '!=', $beritaId)
            ->where('kategori', '!=', $kategori)
            ->selectRaw('berita.*, (view_count + 
            (SELECT COUNT(*) FROM reaksi WHERE jenis_reaksi = "Suka" 
             AND item_id = berita.id AND reaksi_type = "Berita") * 2) as skor')
            ->orderByDesc('skor')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $this->formatBeritaResponse($beritas, $userId)
        ]);
    }


    // Ambil berita rekomendasi berdasarkan kategori like
    public function getBeritaRekomendasi(Request $request)
    {
        $userId = $request->query('user_id');
        [$limit, $page] = $this->getPaginationParams($request);
        $kategoriFavorit = collect();

        if ($userId) {
            // Ambil kategori dari berita yang pernah di-like oleh user
            $kategoriFavorit = Berita::whereHas('reaksis', function ($q) use ($userId) {
                $q->where('user_id', $userId)
                    ->where('jenis_reaksi', 'Suka');
            })->pluck('kategori')->unique();
        }

        if ($kategoriFavorit->isNotEmpty()) {
            // User pernah like berita, ambil berita dari kategori favorit
            $beritas = Berita::with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
                ->whereIn('kategori', $kategoriFavorit)
                ->where('visibilitas', 'public')
                ->orderByDesc('tanggal_diterbitkan')
                ->paginate($limit, ['*'], 'page', $page);
        } else {
            // User belum pernah like atau user_id null
            // $sevenDaysAgo = now()->subDays(7);

            $beritas = Berita::withCount(['reaksis as like_count' => function ($q) {
                $q->where('jenis_reaksi', 'Suka');
            }])
                ->with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
                ->where('visibilitas', 'public')
                // ->where('tanggal_diterbitkan', '>=', $sevenDaysAgo) 
                ->orderByDesc('view_count')
                ->orderByDesc('like_count')
                ->paginate($limit, ['*'], 'page', $page);
        }

        return response()->json([
            'data' => $this->formatBeritaResponse($beritas, $userId)
        ]);
    }



    // Ambil rekomendasi lainnya yang belum di-bookmark user
    public function getRekomendasiLainnya(Request $request)
    {
        $userId = $request->query('user_id');
        [$limit, $page] = $this->getPaginationParams($request);

        $beritas = Berita::with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
            ->where('visibilitas', 'public')
            ->whereDoesntHave('bookmarks', function ($q) use ($userId) {
                if ($userId) {
                    $q->where('user_id', $userId);
                }
            })
            ->inRandomOrder()
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $this->formatBeritaResponse($beritas, $userId)
        ]);
    }

    // Pencarian berita dengan query bebas
    public function searchBerita(Request $request)
    {
        $userId = $request->query('user_id');
        $query = $request->query('q');
        [$limit, $page] = $this->getPaginationParams($request);

        if (!$query) {
            return response()->json(['message' => 'Query pencarian tidak boleh kosong'], 400);
        }

        $beritas = Berita::with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
            ->where('visibilitas', 'public')
            ->where(function ($q) use ($query) {
                $q->where('judul', 'like', "%$query%")
                    // ->orWhere('konten_berita', 'like', "%$query%")
                    ->orWhere('kategori', 'like', "%$query%");
            })
            ->orderByDesc('tanggal_diterbitkan')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $this->formatBeritaResponse($beritas, $userId)
        ]);
    }

    // Cari berita berdasarkan kategori tertentu
    public function searchByKategori(Request $request)
    {
        $userId = $request->query('user_id');
        $kategori = $request->query('kategori');
        [$limit, $page] = $this->getPaginationParams($request);

        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak boleh kosong'], 400);
        }

        $beritas = Berita::with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
            ->where('visibilitas', 'public')
            ->where('kategori', 'like', "%$kategori%")
            ->orderByDesc('tanggal_diterbitkan')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'data' => $this->formatBeritaResponse($beritas, $userId)
        ]);
    }
}


// php artisan serve --host=0.0.0.0 --port=8000