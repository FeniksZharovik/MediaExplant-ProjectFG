<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News\KesehatanNews;
use Illuminate\Http\Request;
use App\Models\UserReact\Reaksi;
use Illuminate\Support\Facades\Auth;
use App\Models\UserReact\Komentar;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KesehatanNewsController extends Controller
{
    /**
     * Tampilkan daftar berita Kesehatan.
     */
    public function index()
    {
        $terbaru = KesehatanNews::with('user')
            ->where('kategori', 'Kesehatan')
            ->where('visibilitas', 'public')
            ->withCount([
                'reaksiSuka as like_count'
            ])
            ->latest('tanggal_diterbitkan')
            ->take(10)
            ->get();

        $mingguLalu = now()->subDays(7)->toDateTimeString();

        $baseQuery = KesehatanNews::with('user')
            ->where('kategori', 'Kesehatan')
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
            ->orderByDesc('tanggal_diterbitkan');

        // Ambil berita minggu ini
        $beritaMingguIni = (clone $baseQuery)
            ->where('tanggal_diterbitkan', '>=', $mingguLalu)
            ->take(10)
            ->get();

        // Filter berita yang punya skor > 0
        $beritaDenganSkor = $beritaMingguIni->filter(function ($item) {
            return $item->score > 0;
        });

        // Hitung kekurangan
        $sisa = 10 - $beritaDenganSkor->count();

        if ($sisa > 0) {
            // Ambil berita lama yang belum tampil
            $idYangSudahDiambil = $beritaMingguIni->pluck('id')->toArray();

            $beritaLainnya = (clone $baseQuery)
                ->where('tanggal_diterbitkan', '<', $mingguLalu)
                ->whereNotIn('berita.id', $idYangSudahDiambil)
                ->take($sisa)
                ->get();

            $beritaDenganSkor = $beritaDenganSkor->concat($beritaLainnya);
        }

        // Jika masih kurang dari 10 (artinya skor semua berita = 0), ambil tambahan dari minggu ini termasuk yang skornya 0
        if ($beritaDenganSkor->count() < 10) {
            $idYangSudahDiambil = $beritaDenganSkor->pluck('id')->toArray();

            $beritaTambahan = (clone $baseQuery)
                ->where('tanggal_diterbitkan', '>=', $mingguLalu)
                ->whereNotIn('berita.id', $idYangSudahDiambil)
                ->take(10 - $beritaDenganSkor->count())
                ->get();

            $terpopuler = $beritaDenganSkor->concat($beritaTambahan);
        } else {
            $terpopuler = $beritaDenganSkor;
        }

        $oneWeekAgo = Carbon::now()->subWeek();

        $rekomendasi = KesehatanNews::with('user')
            ->where('kategori', 'Kesehatan')
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

        return view('kategori.kesehatan', compact('terbaru', 'terpopuler', 'rekomendasi'));
    }

    /**
     * Tampilkan detail berita berdasarkan query parameter.
     */
    public function show(Request $request)
    {
        $newsId = $request->query('a');
        $news = KesehatanNews::where('id', $newsId)->firstOrFail();
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

        $relatedNews = KesehatanNews::where('kategori', $news->kategori)
            ->where('id', '!=', $news->id)
            ->where('visibilitas', 'public')
            ->orderByDesc('view_count')
            ->orderByDesc('tanggal_diterbitkan')
            ->take(6)
            ->get();

        $recommendedNews = KesehatanNews::where('kategori', $news->kategori)
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

        $randomKategori = KesehatanNews::where('kategori', '!=', $news->kategori)
            ->where('visibilitas', 'public')
            ->inRandomOrder()
            ->value('kategori');

        $oneWeekAgo = Carbon::now()->subWeek(); // 7 hari lalu

        // 2. Coba ambil berita dari kategori itu dalam 7 hari terakhir
        $otherTopics = KesehatanNews::where('kategori', $randomKategori)
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

            $tambahan = KesehatanNews::where('kategori', $randomKategori)
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
        $berita = KesehatanNews::with('user')
            ->where('kategori', 'Kesehatan')
            ->where('visibilitas', 'public')
            ->orderByDesc('tanggal_diterbitkan')
            ->paginate(50);

        return view('kategori.other.kesehatan', compact('berita'));
    }
}
