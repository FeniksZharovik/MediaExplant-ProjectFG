<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News\HomeNews;
use Illuminate\Http\Request;
use App\Models\Produk\Buletin;
use App\Models\Produk\Majalah;
use App\Models\Karya\Puisi;
use App\Models\Karya\Pantun;
use App\Models\Karya\Syair;
use App\Models\Karya\Fotografi;
use App\Models\Karya\DesainGrafis;
use Illuminate\Support\Facades\Auth;
use App\Models\UserReact\Reaksi;
use App\Models\UserReact\Komentar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeNewsController extends Controller
{
    /**
     * Tampilkan daftar berita di homepage atau kategori tertentu
     */
    public function index(Request $request, $category = null)
    {
        if (!$category) {
            $news = HomeNews::where('visibilitas', 'public')
                ->orderBy('tanggal_diterbitkan', 'desc')
                ->take(10)
                ->get();

            $newsList = (new HomeNews)->getBeritaTeratasHariIni();

            $sliderNews = HomeNews::where('visibilitas', 'public')
                ->orderBy('tanggal_diterbitkan', 'desc')
                ->take(10)
                ->get();

            $puisiList = Puisi::getTeratasMingguan();

            $pantunList = Pantun::getTeratasMingguan();

            $syairList = Syair::getTeratasMingguan();

            $totalFotografiCount = Fotografi::where('kategori', 'fotografi')
                ->where('visibilitas', 'public')
                ->count();

            $fotografiList = Fotografi::getTeratasMingguan();

            $totalDesainGrafisCount = DesainGrafis::where('kategori', 'desain_grafis')
                ->where('visibilitas', 'public')
                ->count();

            $desainGrafisList = DesainGrafis::getTeratasMingguan();

            // Ambil data buletin & majalah
            $buletinList = Buletin::getHomeBuletin();
            $majalahList = Majalah::getHomeMajalah();

            return view('home', compact('news', 'newsList', 'sliderNews', 'buletinList', 'majalahList', 'puisiList', 'pantunList', 'syairList', 'totalFotografiCount', 'fotografiList', 'desainGrafisList', 'totalDesainGrafisCount'));
        }

        $news = HomeNews::where('kategori', str_replace('-', ' ', $category))
            ->where('visibilitas', 'public')
            ->orderBy('tanggal_diterbitkan', 'desc')
            ->paginate(10);

        return view('kategori.news-list', compact('news', 'category'));
    }

    private function getKaryaByCategory($category)
    {
        switch ($category) {
            case 'puisi':
                return Puisi::where('kategori', 'puisi')->orderBy('release_date', 'desc')->take(6)->get();
            case 'pantun':
                return Pantun::where('kategori', 'pantun')->orderBy('release_date', 'desc')->take(6)->get();
            case 'syair':
                return Syair::where('kategori', 'syair')->orderBy('release_date', 'desc')->take(6)->get();
            case 'fotografi':
                return Fotografi::where('kategori', 'fotografi')->orderBy('release_date', 'desc')->take(6)->get();
            case 'desain_grafis':
                return DesainGrafis::where('kategori', 'desain_grafis')->orderBy('release_date', 'desc')->take(6)->get();
            default:
                return [];
        }
    }

    /**
     * Tampilkan detail berita
     */
    public function show(Request $request)
    {
        $newsId = $request->query('a');
        $news = HomeNews::where('id', $newsId)->firstOrFail();
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
            ->whereNull('parent_id')
            ->orderBy('tanggal_komentar', 'desc')
            ->get();

        // RELATED NEWS: view_count + tanggal terbaru
        $relatedNews = HomeNews::where('kategori', $news->kategori)
            ->where('id', '!=', $news->id)
            ->where('visibilitas', 'public')
            ->orderByDesc('tanggal_diterbitkan')
            ->orderByDesc('view_count')
            ->take(6)
            ->get();

        // RECOMMENDED NEWS: suka_count + view_count + tanggal terbaru
        $recommendedNews = HomeNews::where('kategori', $news->kategori)
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

        // OTHER TOPICS: kategori acak, lalu view_count + suka_count + tanggal
        $randomKategori = HomeNews::where('kategori', '!=', $news->kategori)
            ->where('visibilitas', 'public')
            ->inRandomOrder()
            ->value('kategori');

        $oneWeekAgo = Carbon::now()->subWeek(); // 7 hari lalu

        // 2. Coba ambil berita dari kategori itu dalam 7 hari terakhir
        $otherTopics = HomeNews::where('kategori', $randomKategori)
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

            $tambahan = HomeNews::where('kategori', $randomKategori)
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
}
