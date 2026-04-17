<?php

namespace App\Http\Controllers\Karya;

use App\Http\Controllers\Controller;
use App\Models\Karya\DesainGrafis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserReact\Reaksi;
use App\Models\UserReact\Komentar;
use Illuminate\Support\Facades\DB;

class DesainGrafisController extends Controller
{
    // Menampilkan daftar karya desain grafis
    public function index()
    {
        $karya = DesainGrafis::with('user')
            ->where('kategori', 'desain_grafis')
            ->where('visibilitas', 'public')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS like_count
         FROM reaksi
         WHERE jenis_reaksi = 'Suka' AND reaksi_type = 'Karya'
         GROUP BY item_id) as r
    "), 'karya.id', '=', 'r.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS komentar_count
         FROM komentar
         WHERE komentar_type = 'Karya'
         GROUP BY item_id) as k
    "), 'karya.id', '=', 'k.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS bookmark_count
         FROM bookmark
         WHERE bookmark_type = 'Karya'
         GROUP BY item_id) as b
    "), 'karya.id', '=', 'b.item_id')
            ->select(
                'karya.*',
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
            ->orderByDesc('release_date')
            ->paginate(12);

        return view('karya.desain-grafis', compact('karya'));
    }

    // Menampilkan detail karya
    public function show()
    {
        $id = request()->get('k');

        $karya = DesainGrafis::with('user')
            ->where('id', $id)
            ->where('kategori', 'desain_grafis')
            ->where('visibilitas', 'public')
            ->firstOrFail();

        $karya->increment('view_count');

        $rekomendasi = DesainGrafis::from('karya as p')
            ->with('user')
            ->where('p.kategori', 'desain_grafis')
            ->where('p.visibilitas', 'public')
            ->where('p.id', '!=', $id)
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS like_count
         FROM reaksi
         WHERE jenis_reaksi = 'Suka' AND reaksi_type = 'Karya'
         GROUP BY item_id) as r
    "), 'p.id', '=', 'r.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS komentar_count
         FROM komentar
         WHERE komentar_type = 'Karya'
         GROUP BY item_id) as k
    "), 'p.id', '=', 'k.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS bookmark_count
         FROM bookmark
         WHERE bookmark_type = 'Karya'
         GROUP BY item_id) as b
    "), 'p.id', '=', 'b.item_id')
            ->select(
                'p.*',
                DB::raw('
            (p.view_count * 1) +
            (COALESCE(r.like_count, 0) * 2) +
            (COALESCE(k.komentar_count, 0) * 3) +
            (COALESCE(b.bookmark_count, 0) * 2) as score
        ')
            )
            ->orderByDesc('score')
            ->orderByDesc('p.release_date')
            ->take(4)
            ->get();

        $komentarList = Komentar::with(['user', 'replies.user'])
            ->where('komentar_type', 'Karya')
            ->where('item_id', $karya->id)
            ->whereNull('parent_id')
            ->orderBy('tanggal_komentar', 'desc')
            ->get();

        $likeCount = Reaksi::where('item_id', $karya->id)
            ->where('jenis_reaksi', 'Suka')
            ->where('reaksi_type', 'Karya')
            ->count();

        $dislikeCount = Reaksi::where('item_id', $karya->id)
            ->where('jenis_reaksi', 'Tidak Suka')
            ->where('reaksi_type', 'Karya')
            ->count();

        $userReaksi = null;
        if (Auth::check()) {
            $userReaksi = Reaksi::where('user_id', Auth::user()->uid)
                ->where('item_id', $karya->id)
                ->where('reaksi_type', 'Karya')
                ->first();
        }

        return view('karya.detail.desainGrafis-detail', compact('karya', 'rekomendasi', 'likeCount', 'dislikeCount', 'komentarList', 'userReaksi'));
    }

    public function semua()
    {
        $karya = DesainGrafis::with('user')
            ->where('kategori', 'desain_grafis')
            ->where('visibilitas', 'public')
            ->orderByDesc('release_date')
            ->paginate(50);

        return view('karya.other.desain-grafis', compact('karya'));
    }
}
