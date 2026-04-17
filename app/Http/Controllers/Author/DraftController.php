<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author\Draft;
use App\Models\Author\Karya;
use App\Models\Author\Produk;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class DraftController extends Controller
{
    public function index(Request $request)
    {
        $user = Session::get('user');
        Carbon::setLocale('id');

        $search = $request->input('search');
        $searchRaw = $search;
        $search = str_replace(' ', '_', $searchRaw);
        $sort = $request->input('sort');

        // ========== Ambil Berita ==========
        $beritaQuery = Draft::select('id', 'judul', 'kategori', 'konten_berita', 'tanggal_diterbitkan')
            ->where('user_id', $user->uid)
            ->where('visibilitas', 'private');

        if ($search) {
            $beritaQuery->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('kategori', 'like', "%$search%");
            });
        }

        $this->applySorting($beritaQuery, $sort, 'judul', 'tanggal_diterbitkan');

        $berita = $beritaQuery->get()->map(function ($item) {
            preg_match('/<img.*?src=["\']([^"\']+)/', $item->konten_berita, $matches);
            $thumbnail = $matches[1] ?? asset('images/default-thumbnail.jpg');
            $tanggal = Carbon::parse($item->tanggal_diterbitkan);

            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'kategori' => $item->kategori,
                'thumbnail' => $thumbnail,
                'draft_ago' => $tanggal->diffForHumans(now(), ['parts' => 1, 'syntax' => Carbon::DIFF_RELATIVE_TO_NOW]),
                'tanggal_dibuat' => $item->tanggal_diterbitkan,
                'tipe' => 'berita',
            ];
        });

        // ========== Ambil Karya ==========
        $karyaQuery = Karya::select('id', 'judul', 'kategori', 'media', 'release_date')
            ->where('user_id', $user->uid)
            ->where('visibilitas', 'private');

        if ($search) {
            $karyaQuery->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('kategori', 'like', "%$search%");
            });
        }

        $this->applySorting($karyaQuery, $sort, 'judul', 'release_date');

        $karya = $karyaQuery->get()->map(function ($item) {
            $thumbnail = 'data:image/jpeg;base64,' . $item->media;
            $tanggal = Carbon::parse($item->release_date);

            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'kategori' => $item->kategori,
                'thumbnail' => $thumbnail,
                'draft_ago' => $tanggal->diffForHumans(now(), ['parts' => 1, 'syntax' => Carbon::DIFF_RELATIVE_TO_NOW]),
                'tanggal_dibuat' => $item->release_date,
                'tipe' => 'karya',
            ];
        });

        // ========== Ambil Produk ==========
        $produkQuery = Produk::select('id', 'judul', 'kategori', 'cover', 'release_date')
            ->where('user_id', $user->uid)
            ->where('visibilitas', 'private');

        if ($search) {
            $produkQuery->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('kategori', 'like', "%$search%");
            });
        }

        $this->applySorting($produkQuery, $sort, 'judul', 'release_date');

        $produk = $produkQuery->get()->map(function ($item) {
            $cover = $item->cover;
            $thumbnail = asset('images/default-thumbnail.jpg');

            if ($cover) {
                if (str_starts_with($cover, 'data:image/')) {
                    $thumbnail = $cover;
                } else {
                    $prefix = 'data:image/jpeg;base64,';
                    if (str_starts_with($cover, 'iVBOR')) {
                        $prefix = 'data:image/png;base64,';
                    } elseif (str_starts_with($cover, '/9j/')) {
                        $prefix = 'data:image/jpeg;base64,';
                    }
                    $thumbnail = $prefix . $cover;
                }
            }

            $tanggal = Carbon::parse($item->release_date);

            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'kategori' => $item->kategori,
                'thumbnail' => $thumbnail,
                'draft_ago' => $tanggal->diffForHumans(now(), ['parts' => 1, 'syntax' => Carbon::DIFF_RELATIVE_TO_NOW]),
                'tanggal_dibuat' => $item->release_date,
                'tipe' => 'produk',
            ];
        });

        // ========== Gabungkan Semua ==========
        $all = $berita->concat($karya)->concat($produk)->sortByDesc('tanggal_dibuat')->values();

        if ($all->count() <= 100) {
            return view('authors.draft', [
                'berita' => $all,
                'paginate' => false,
            ]);
        }

        // ========== Pagination ==========
        $perPage = 100;
        $currentPage = $request->get('page', 1);
        $paged = $all->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $paged,
            $all->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return view('authors.draft', [
            'berita' => $paginator,
            'paginate' => true,
        ]);
    }

    private function applySorting($query, $sort, $titleColumn, $dateColumn)
    {
        if ($sort === 'asc') {
            $query->orderBy($titleColumn, 'asc');
        } elseif ($sort === 'desc') {
            $query->orderBy($titleColumn, 'desc');
        } else {
            $query->orderBy($dateColumn, 'desc');
        }
    }

    public function edit($id)
    {
        $user = session('user');
        $berita = Draft::where('id', $id)->where('user_id', $user->uid)->firstOrFail();
        return view('authors.edit', compact('berita'));
    }

    public function destroy($id, Request $request)
    {
        $user = session('user');
        $tipe = $request->input('tipe');

        switch ($tipe) {
            case 'berita':
                $item = Draft::where('id', $id)->where('user_id', $user->uid)->firstOrFail();
                break;
            case 'karya':
                $item = Karya::where('id', $id)->where('user_id', $user->uid)->firstOrFail();
                break;
            case 'produk':
                $item = Produk::where('id', $id)->where('user_id', $user->uid)->firstOrFail();
                break;
            default:
                abort(404);
        }

        $item->delete();

        return redirect()->route('draft-media')->with('success', ucfirst($tipe) . ' berhasil dihapus.');
    }
}
