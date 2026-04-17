<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News\OpiniEsaiNews;
use Illuminate\Http\Request;
use App\Models\UserReact\Reaksi;
use Illuminate\Support\Facades\Auth;
use App\Models\UserReact\Komentar;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OpiniEsaiNewsController extends Controller
{
    /**
     * Tampilkan daftar berita Opini dan Esai.
     */
    public function index()
    {
        $terbaru = OpiniEsaiNews::with('user')
            ->whereIn('kategori', ['Opini', 'Esai'])
            ->where('visibilitas', 'public')
            ->withCount([
                'reaksiSuka as like_count'
            ])
            ->latest('tanggal_diterbitkan')
            ->take(10)
            ->get();

        $oneWeekAgo = Carbon::now()->subWeek();

        $rekomendasi = OpiniEsaiNews::with('user')
            ->whereIn('kategori', ['Opini', 'Esai'])
            ->where('visibilitas', 'public')
            ->select('berita.*', DB::raw("
        (
            berita.view_count +
            (
                SELECT COUNT(*)
                FROM reaksi
                WHERE reaksi.item_id = berita.id
                  AND reaksi.jenis_reaksi = 'Suka'
                  AND reaksi.reaksi_type = 'Berita'
            )
        ) as total_interaksi
    "))
            ->orderByRaw("
        CASE
            WHEN tanggal_diterbitkan >= ? THEN 0
            ELSE 1
        END, total_interaksi DESC
    ", [$oneWeekAgo])
            ->take(8)
            ->get();

        $oneWeekAgo = Carbon::now()->subWeek()->toDateTimeString();

        function getPopularNews($kategori)
        {
            global $oneWeekAgo;

            $baseQuery = DB::table('berita')
                ->leftJoin('user', 'user.uid', '=', 'berita.user_id')
                ->leftJoin('komentar as km', function ($join) {
                    $join->on('berita.id', '=', 'km.item_id')
                        ->where('km.komentar_type', '=', 'Berita');
                })
                ->leftJoin('reaksi as rk', function ($join) {
                    $join->on('berita.id', '=', 'rk.item_id')
                        ->where('rk.reaksi_type', '=', 'Berita')
                        ->where('rk.jenis_reaksi', '=', 'Suka');
                })
                ->where('berita.kategori', $kategori)
                ->where('berita.visibilitas', 'public')
                ->whereRaw("CAST(berita.tanggal_diterbitkan AS DATETIME) >= ?", [$oneWeekAgo])
                ->groupBy(
                    'berita.id',
                    'berita.judul',
                    'berita.kategori',
                    'berita.konten_berita',
                    'berita.tanggal_diterbitkan',
                    'berita.view_count',
                    'user.nama_lengkap'
                )
                ->select(
                    'berita.id',
                    'berita.judul',
                    'berita.kategori',
                    'berita.konten_berita',
                    'berita.tanggal_diterbitkan',
                    'berita.view_count',
                    'user.nama_lengkap as user_nama_lengkap',
                    DB::raw('COUNT(DISTINCT rk.id) as like_count'),
                    DB::raw('COUNT(DISTINCT km.id) as komentar_count'),
                    DB::raw('(berita.view_count + COUNT(DISTINCT rk.id) + COUNT(DISTINCT km.id)) as total_score')
                )
                ->orderByDesc('total_score')
                ->take(5);

            $result = $baseQuery->get();

            if ($result->isEmpty()) {
                $fallbackQuery = DB::table('berita')
                    ->leftJoin('user', 'user.uid', '=', 'berita.user_id')
                    ->leftJoin('komentar as km', function ($join) {
                        $join->on('berita.id', '=', 'km.item_id')
                            ->where('km.komentar_type', '=', 'Berita');
                    })
                    ->leftJoin('reaksi as rk', function ($join) {
                        $join->on('berita.id', '=', 'rk.item_id')
                            ->where('rk.reaksi_type', '=', 'Berita')
                            ->where('rk.jenis_reaksi', '=', 'Suka');
                    })
                    ->where('berita.kategori', $kategori)
                    ->where('berita.visibilitas', 'public')
                    ->groupBy(
                        'berita.id',
                        'berita.judul',
                        'berita.kategori',
                        'berita.konten_berita',
                        'berita.tanggal_diterbitkan',
                        'berita.view_count',
                        'user.nama_lengkap'
                    )
                    ->select(
                        'berita.id',
                        'berita.judul',
                        'berita.kategori',
                        'berita.konten_berita',
                        'berita.tanggal_diterbitkan',
                        'berita.view_count',
                        'user.nama_lengkap as user_nama_lengkap',
                        DB::raw('COUNT(DISTINCT rk.id) as like_count'),
                        DB::raw('COUNT(DISTINCT km.id) as komentar_count'),
                        DB::raw('(berita.view_count + COUNT(DISTINCT rk.id) + COUNT(DISTINCT km.id)) as total_score')
                    )
                    ->orderByDesc('total_score')
                    ->take(5);

                $result = $fallbackQuery->get();
            }

            return $result->map(function ($item) {
                // Extract first image from konten_berita
                preg_match('/<img[^>]+src="([^">]+)"/i', $item->konten_berita, $matches);
                $item->first_image = $matches[1] ?? 'https://via.placeholder.com/400x200';

                // Buat properti user sebagai object agar mirip relasi
                $item->user = (object)[
                    'nama_lengkap' => $item->user_nama_lengkap ?? '-'
                ];

                return $item;
            });
        }

        $terpopuler_opini = getPopularNews('Opini');
        $terpopuler_esai = getPopularNews('Esai');

        return view('kategori.opini-esai', compact(
            'terbaru',
            'rekomendasi',
            'terpopuler_opini',
            'terpopuler_esai'
        ));
    }

    /**
     * Tampilkan detail berita berdasarkan query parameter.
     */
    public function show(Request $request)
    {
        $newsId = $request->query('a');
        $news = OpiniEsaiNews::where('id', $newsId)->firstOrFail();
        $news->increment('view_count');

        $likeCount = Reaksi::where('item_id', $news->id)
            ->where('jenis_reaksi', 'Suka')
            ->count();

        $dislikeCount = Reaksi::where('item_id', $news->id)
            ->where('jenis_reaksi', 'Tidak Suka')
            ->count();

        $userReaksi = null;
        if (Auth::check()) {
            $userReaksi = Reaksi::where('user_id', Auth::user()->uid)
                ->where('item_id', $news->id)
                ->where('reaksi_type', 'Berita')
                ->first();
        }

        $komentarList = Komentar::with(['user', 'replies.user'])
            ->where('komentar_type', 'Berita')
            ->where('item_id', $news->id)
            ->whereNull('parent_id') // hanya komentar utama
            ->orderBy('tanggal_komentar', 'desc')
            ->get();

        $relatedNews = OpiniEsaiNews::where('kategori', $news->kategori)
            ->where('id', '!=', $news->id)
            ->where('visibilitas', 'public')
            ->orderByDesc('tanggal_diterbitkan')
            ->orderByDesc('view_count')
            ->take(6)
            ->get();

        $recommendedNews = OpiniEsaiNews::where('kategori', $news->kategori)
            ->where('id', '!=', $news->id)
            ->where('visibilitas', 'public')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS like_count
         FROM reaksi
         WHERE jenis_reaksi = 'Suka' AND reaksi_type = 'Berita'
         GROUP BY item_id) as r
    "), 'berita.id', '=', 'r.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS komentar_count
         FROM komentar
         WHERE komentar_type = 'Berita'
         GROUP BY item_id) as k
    "), 'berita.id', '=', 'k.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS bookmark_count
         FROM bookmark
         WHERE bookmark_type = 'Berita'
         GROUP BY item_id) as b
    "), 'berita.id', '=', 'b.item_id')
            ->select(
                'berita.*',
                DB::raw('COALESCE(r.like_count, 0) as like_count'),
                DB::raw('COALESCE(k.komentar_count, 0) as komentar_count'),
                DB::raw('COALESCE(b.bookmark_count, 0) as bookmark_count'),
                DB::raw('
            (view_count * 1) +
            (COALESCE(r.like_count, 0) * 2) +
            (COALESCE(k.komentar_count, 0) * 3) +
            (COALESCE(b.bookmark_count, 0) * 2) as score
        ')
            )
            ->orderByDesc('score')
            ->orderByDesc('tanggal_diterbitkan')
            ->take(6)
            ->get();

        $randomKategori = OpiniEsaiNews::where('kategori', '!=', $news->kategori)
            ->where('visibilitas', 'public')
            ->inRandomOrder()
            ->value('kategori');

        $oneWeekAgo = Carbon::now()->subWeek(); // 7 hari lalu

        // 2. Coba ambil berita dari kategori itu dalam 7 hari terakhir
        $otherTopics = OpiniEsaiNews::where('kategori', $randomKategori)
            ->where('visibilitas', 'public')
            ->where('tanggal_diterbitkan', '>=', $oneWeekAgo)
            ->withCount([
                'reaksiSuka as suka_count'
            ])
            ->orderByDesc('view_count')
            ->orderByDesc('suka_count')
            ->orderByDesc('tanggal_diterbitkan')
            ->take(8)
            ->get();

        // 3. Jika hasilnya kurang dari 8, ambil tambahan berita dari kategori tersebut (di luar 7 hari)
        if ($otherTopics->count() < 8) {
            $sisa = 8 - $otherTopics->count();
            $idYangSudahDiambil = $otherTopics->pluck('id')->toArray();

            $tambahan = OpiniEsaiNews::where('kategori', $randomKategori)
                ->where('visibilitas', 'public')
                ->whereNotIn('id', $idYangSudahDiambil)
                ->withCount([
                    'reaksiSuka as suka_count'
                ])
                ->orderByDesc('view_count')
                ->orderByDesc('suka_count')
                ->orderByDesc('tanggal_diterbitkan')
                ->take($sisa)
                ->get();

            $otherTopics = $otherTopics->concat($tambahan);
        }

        return view('kategori.news-detail', compact('news', 'relatedNews', 'recommendedNews', 'otherTopics', 'likeCount', 'dislikeCount', 'userReaksi', 'komentarList'));
    }

    public function semua()
    {
        $berita = OpiniEsaiNews::with('user')
            ->whereIn('kategori', ['Opini', 'Esai'])
            ->where('visibilitas', 'public')
            ->orderByDesc('tanggal_diterbitkan')
            ->paginate(50);

        return view('kategori.other.opini-esai', compact('berita'));
    }
}
