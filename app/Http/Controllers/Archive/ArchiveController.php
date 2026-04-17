<?php

namespace App\Http\Controllers\Archive;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArchiveController extends Controller
{
    public function index()
    {
        $productYears = DB::table('produk')
            ->selectRaw('YEAR(release_date) as year')
            ->whereNotNull('release_date')
            ->distinct()
            ->pluck('year');

        $newsYears = DB::table('berita')
            ->selectRaw('YEAR(tanggal_diterbitkan) as year')
            ->whereNotNull('tanggal_diterbitkan')
            ->distinct()
            ->pluck('year');

        $karyaYears = DB::table('karya')
            ->selectRaw('YEAR(release_date) as year')
            ->whereNotNull('release_date')
            ->distinct()
            ->pluck('year');

        $years = $productYears
            ->merge($newsYears)
            ->merge($karyaYears)
            ->unique()
            ->sortDesc();

        return view('archive.index', compact('years'));
    }

    public function show($year)
    {
        // ✅ Berita
        $berita = DB::table('berita')
            ->select('id', 'judul', 'kategori', 'tanggal_diterbitkan', 'konten_berita')
            ->whereYear('tanggal_diterbitkan', $year)
            ->where('visibilitas', 'public')
            ->whereNotNull('tanggal_diterbitkan')
            ->get()
            ->map(function ($item) {
                $item->thumbnail = $this->extractFirstImage($item->konten_berita) ?? asset('images/default-thumbnail.jpg');
                return $item;
            })
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal_diterbitkan)->format('m');
            });

        // ✅ Produk
        $produk = DB::table('produk')
            ->select('id', 'judul', 'kategori', 'release_date', 'cover', 'deskripsi')
            ->whereYear('release_date', $year)
            ->where('visibilitas', 'public')
            ->whereNotNull('release_date')
            ->get()
            ->map(function ($item) {
                $item->thumbnail = (!empty($item->cover) && Str::startsWith($item->cover, 'data:image/'))
                    ? $item->cover
                    : asset('images/default-thumbnail.jpg');
                return $item;
            })
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->release_date)->format('m');
            });

        // ✅ Karya
        $karya = DB::table('karya')
            ->select('id', 'judul', 'kategori', 'release_date', 'media', 'deskripsi')
            ->whereYear('release_date', $year)
            ->where('visibilitas', 'public')
            ->whereNotNull('release_date')
            ->get()
            ->map(function ($item) {
                $item->thumbnail = (!empty($item->media) && strlen($item->media) > 50)
                    ? 'data:image/jpeg;base64,' . $item->media
                    : asset('images/default-thumbnail.jpg');
                return $item;
            })
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->release_date)->format('m');
            });

        return response()->json([
            'berita' => $berita,
            'produk' => $produk,
            'karya' => $karya,
        ]);
    }

    private function extractFirstImage($html)
    {
        if (empty($html)) return null;

        $cleanHtml = str_replace('&nbsp;', ' ', $html);
        preg_match('/<img[^>]+src=["\']?([^"\'>]+)["\']?/i', $cleanHtml, $matches);
        return $matches[1] ?? null;
    }
}
