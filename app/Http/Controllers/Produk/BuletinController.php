<?php

namespace App\Http\Controllers\Produk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk\Buletin;
use Illuminate\Support\Facades\Response;
use App\Models\UserReact\Reaksi;
use Illuminate\Support\Facades\Auth;
use App\Models\UserReact\Komentar;
use Illuminate\Support\Facades\DB;

class BuletinController extends Controller
{
    // Menampilkan buletin utama dan daftar lainnya
    public function index()
    {
        // 3 buletin terbaru untuk "Produk Kami"
        $buletins = Buletin::from('produk as p')
            ->where('p.kategori', 'Buletin')
            ->where('p.visibilitas', 'public')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS like_count
         FROM reaksi
         WHERE jenis_reaksi = 'Suka' AND reaksi_type = 'Produk'
         GROUP BY item_id) as r
    "), 'p.id', '=', 'r.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS komentar_count
         FROM komentar
         WHERE komentar_type = 'Produk'
         GROUP BY item_id) as k
    "), 'p.id', '=', 'k.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS bookmark_count
         FROM bookmark
         WHERE bookmark_type = 'Produk'
         GROUP BY item_id) as b
    "), 'p.id', '=', 'b.item_id')
            ->select(
                'p.id',
                'p.judul',
                'p.cover',
                'p.deskripsi',
                'p.release_date',
                'p.user_id',
                DB::raw('
            (p.view_count * 1) +
            (COALESCE(r.like_count, 0) * 2) +
            (COALESCE(k.komentar_count, 0) * 3) +
            (COALESCE(b.bookmark_count, 0) * 2) as score
        ')
            )
            ->orderByDesc('score')
            ->orderByDesc('p.release_date')
            ->take(3)
            ->get();

        // 9 buletin terbaru untuk "Terbaru"
        $buletinsTerbaru = Buletin::select('id', 'judul', 'cover', 'release_date', 'user_id')
            ->where('kategori', 'Buletin')
            ->where('visibilitas', 'public')
            ->orderBy('release_date', 'desc')
            ->take(9)
            ->get();

        // 12 buletin rekomendasi terbaru
        $buletinsRekomendasi = Buletin::from('produk as p')
            ->where('p.kategori', 'Buletin')
            ->where('p.visibilitas', 'public')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS like_count
         FROM reaksi
         WHERE jenis_reaksi = 'Suka' AND reaksi_type = 'Produk'
         GROUP BY item_id) as r
    "), 'p.id', '=', 'r.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS komentar_count
         FROM komentar
         WHERE komentar_type = 'Produk'
         GROUP BY item_id) as k
    "), 'p.id', '=', 'k.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS bookmark_count
         FROM bookmark
         WHERE bookmark_type = 'Produk'
         GROUP BY item_id) as b
    "), 'p.id', '=', 'b.item_id')
            ->select(
                'p.id',
                'p.judul',
                'p.cover',
                'p.release_date',
                'p.user_id',
                DB::raw('
            (p.view_count * 1) +
            (COALESCE(r.like_count, 0) * 2) +
            (COALESCE(k.komentar_count, 0) * 3) +
            (COALESCE(b.bookmark_count, 0) * 2) as score
        ')
            )
            ->orderByDesc('score')
            ->orderByDesc('p.release_date')
            ->take(12)
            ->get();

        return view('produk.buletin', compact('buletins', 'buletinsTerbaru', 'buletinsRekomendasi'));
    }

    // Menampilkan halaman detail buletin dengan optimasi memori
    public function show(Request $request)
    {
        $id = $request->query('f');

        // Ambil buletin utama tanpa media besar, hanya kolom penting saja
        $buletin = Buletin::select('id', 'judul', 'cover', 'deskripsi', 'release_date', 'user_id', 'kategori', 'visibilitas')
            ->where('visibilitas', 'public')
            ->where('id', $id)
            ->where('kategori', 'Buletin')
            ->first();

        if (!$buletin) {
            return abort(404, "Buletin tidak ditemukan.");
        }

        $buletin->increment('view_count');

        // Pagination rekomendasi buletin dengan limit dan tanpa eager loading user (jika user tidak dibutuhkan)
        $rekomendasiBuletin = Buletin::from('produk as p')
            ->where('p.kategori', 'Buletin')
            ->where('p.visibilitas', 'public')
            ->where('p.id', '!=', $id)
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS like_count
         FROM reaksi
         WHERE jenis_reaksi = 'Suka' AND reaksi_type = 'Produk'
         GROUP BY item_id) as r
    "), 'p.id', '=', 'r.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS komentar_count
         FROM komentar
         WHERE komentar_type = 'Produk'
         GROUP BY item_id) as k
    "), 'p.id', '=', 'k.item_id')
            ->leftJoin(DB::raw("
        (SELECT item_id, COUNT(*) AS bookmark_count
         FROM bookmark
         WHERE bookmark_type = 'Produk'
         GROUP BY item_id) as b
    "), 'p.id', '=', 'b.item_id')
            ->select(
                'p.id',
                'p.judul',
                'p.cover',
                'p.release_date',
                DB::raw('
            (p.view_count * 1) +
            (COALESCE(r.like_count, 0) * 2) +
            (COALESCE(k.komentar_count, 0) * 3) +
            (COALESCE(b.bookmark_count, 0) * 2) as score
        ')
            )
            ->orderByDesc('score')
            ->orderByDesc('p.release_date')
            ->paginate(6);

        // Ambil komentar utama dan balasan untuk 'Produk' (komentar_type)
        $komentarList = Komentar::with(['user', 'replies.user'])
            ->where('komentar_type', 'Produk')
            ->where('item_id', $buletin->id)
            ->whereNull('parent_id') // hanya komentar utama
            ->orderBy('tanggal_komentar', 'desc')
            ->get();

        // Hitung like dan dislike secara efisien dengan tambahan filter reaksi_type = Produk
        $likeCount = Reaksi::where('item_id', $buletin->id)
            ->where('jenis_reaksi', 'Suka')
            ->where('reaksi_type', 'Produk')
            ->count();

        $dislikeCount = Reaksi::where('item_id', $buletin->id)
            ->where('jenis_reaksi', 'Tidak Suka')
            ->where('reaksi_type', 'Produk')
            ->count();

        // Ambil reaksi user yang sudah login, dengan filter reaksi_type = Produk
        $userReaksi = null;
        if (Auth::check()) {
            $userReaksi = Reaksi::where('user_id', Auth::user()->uid)
                ->where('item_id', $buletin->id)
                ->where('reaksi_type', 'Produk')
                ->first();
        }

        // Jika AJAX untuk pagination rekomendasi
        if ($request->ajax()) {
            return view('produk.partials.BuletinRekomendasi', compact('rekomendasiBuletin'))->render();
        }

        // Tampilkan view lengkap dengan data hitung reaksi dan user reaksi
        return view('produk.buletin_detail', compact(
            'buletin',
            'komentarList',
            'rekomendasiBuletin',
            'likeCount',
            'dislikeCount',
            'userReaksi'
        ));
    }

    // Menampilkan halaman pertama PDF sebagai thumbnail
    public function pdfPreview($id)
    {
        $buletin = Buletin::findOrFail($id);

        if (!$buletin || !$buletin->media) {
            return abort(404, "PDF tidak ditemukan.");
        }

        return Response::make($buletin->media, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    // Download PDF
    public function download($id)
    {
        $buletin = Buletin::findOrFail($id);

        if (!$buletin || !$buletin->media) {
            return abort(404, "PDF tidak ditemukan.");
        }

        $filename = str_replace(' ', '_', $buletin->judul) . '.pdf';

        return Response::make($buletin->media, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // Preview PDF
    public function preview(Request $request)
    {
        $id = $request->query('f');

        $buletin = Buletin::findOrFail($id);

        if (!$buletin || !$buletin->media) {
            return abort(404, "Buletin tidak ditemukan.");
        }

        return view('produk.buletin_preview', compact('buletin'));
    }

    public function semua()
    {
        $buletins = \App\Models\Produk\Buletin::where('kategori', 'Buletin')
        ->where('visibilitas', 'public')
        ->orderByDesc('release_date')
        ->paginate(50);

        return view('produk.other.buletin', compact('buletins'));
    }
}
