<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile\Bookmarked;
use App\Models\Produk\Buletin;
use App\Models\Produk\Majalah;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class BookmarkedController extends Controller
{
    public function index(Request $request)
    {
        $user = Session::get('user');
        Carbon::setLocale('id');

        $query = Bookmarked::with(['berita', 'karya'])
            ->where('user_id', $user->uid);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = str_replace('_', ' ', strtolower($request->search));

            $query->where(function ($q) use ($search) {
                // Berita
                $q->where(function ($q1) use ($search) {
                    $q1->where('bookmark_type', 'Berita')
                        ->whereHas('berita', function ($q2) use ($search) {
                            $q2->whereRaw('LOWER(REPLACE(kategori, "_", " ")) like ?', ["%{$search}%"])
                                ->orWhereRaw('LOWER(judul) like ?', ["%{$search}%"]);
                        });
                })
                    // Karya
                    ->orWhere(function ($q3) use ($search) {
                        $q3->where('bookmark_type', 'Karya')
                            ->whereHas('karya', function ($q4) use ($search) {
                                $q4->whereRaw('LOWER(REPLACE(kategori, "_", " ")) like ?', ["%{$search}%"])
                                    ->orWhereRaw('LOWER(judul) like ?', ["%{$search}%"]);
                            });
                    })
                    // Produk: Buletin & Majalah
                    ->orWhere(function ($q5) use ($search) {
                        $buletinIds = Buletin::whereRaw('LOWER(REPLACE(kategori, "_", " ")) like ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(judul) like ?', ["%{$search}%"])
                            ->pluck('id')
                            ->toArray();

                        $majalahIds = Majalah::whereRaw('LOWER(REPLACE(kategori, "_", " ")) like ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(judul) like ?', ["%{$search}%"])
                            ->pluck('id')
                            ->toArray();

                        $allIds = array_merge($buletinIds, $majalahIds);

                        $q5->where('bookmark_type', 'Produk')
                            ->whereIn('item_id', $allIds);
                    });
            });
        }

        // Sorting
        $query->orderBy('tanggal_bookmark', $request->sort === 'asc' ? 'asc' : 'desc');

        // Pagination
        $bookmarkedRaw = $query->paginate(50);
        $bookmarkedRaw->appends($request->query());

        // Proses item bookmark
        $bookmarkedItems = collect($bookmarkedRaw->items())->filter(function ($bookmark) {
            switch ($bookmark->bookmark_type) {
                case 'Berita':
                    return $bookmark->berita && $bookmark->berita->visibilitas === 'public';
                case 'Karya':
                    return $bookmark->karya && $bookmark->karya->visibilitas === 'public';
                case 'Produk':
                    $produk = $this->getProduk($bookmark->item_id);
                    return $produk && $produk->visibilitas === 'public';
                default:
                    return false;
            }
        })->map(function ($bookmark) {
            switch ($bookmark->bookmark_type) {
                case 'Berita':
                    $item = $bookmark->berita;
                    preg_match('/<img.*?src=["\']([^"\']+)/', $item->konten_berita, $matches);
                    $thumbnail = $matches[1] ?? asset('images/default-thumbnail.jpg');

                    $kategoriRaw = $item->kategori ?? 'Berita';
                    $kategoriName = strtoupper($kategoriRaw);

                    $kategoriSlug = match (strtolower($kategoriRaw)) {
                        'opini', 'esai' => 'opini-esai',
                        'nasional', 'internasional' => 'nasional-internasional',
                        'liputan khusus' => 'liputan-khusus',
                        'kesenian', 'hiburan' => 'kesenian-hiburan',
                        default => str_replace(' ', '-', strtolower($kategoriRaw)),
                    };

                    $judul = $item->judul;
                    $url = url("/kategori/{$kategoriSlug}/read?a={$item->id}");
                    break;

                case 'Karya':
                    $item = $bookmark->karya;
                    $thumbnail = 'data:image/jpeg;base64,' . $item->media;
                    $kategoriName = strtoupper(str_replace('_', ' ', $item->kategori));
                    $kategoriSlug = str_replace('_', '-', strtolower($item->kategori));
                    $judul = $item->judul;
                    $url = url("/karya/{$kategoriSlug}/read?k={$item->id}");
                    break;

                case 'Produk':
                    $item = $this->getProduk($bookmark->item_id);
                    $thumbnail = $item->cover ?? asset('images/default-thumbnail.jpg');
                    $kategoriName = strtoupper(str_replace('_', ' ', $item->kategori ?? 'Produk'));
                    $judul = $item->judul;
                    $url = url("/produk/" . strtolower($item->kategori ?? 'produk') . "/browse?f={$item->id}");
                    break;

                default:
                    return null;
            }

            return [
                'id' => $bookmark->item_id,
                'judul' => $judul,
                'kategori' => $kategoriName,
                'thumbnail' => $thumbnail,
                'tanggal_disimpan' => $bookmark->tanggal_bookmark,
                'disimpan_ago' => Carbon::parse($bookmark->tanggal_bookmark)->diffForHumans(),
                'url' => $url,
            ];
        })->filter();

        // Replace collection on paginator
        $bookmarkedRaw = new \Illuminate\Pagination\LengthAwarePaginator(
            $bookmarkedItems->values(),
            $bookmarkedRaw->total(),
            $bookmarkedRaw->perPage(),
            $bookmarkedRaw->currentPage(),
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('profile.bookmarked', ['bookmarkedItems' => $bookmarkedRaw]);
    }

    protected function getProduk($id)
    {
        return Buletin::find($id) ?? Majalah::find($id);
    }

    public function destroy($id)
    {
        $user = Session::get('user');

        $bookmark = Bookmarked::where('user_id', $user->uid)
            ->where('item_id', $id)
            ->firstOrFail();

        $bookmark->delete();

        return redirect()->route('bookmarked')->with('success', 'Berhasil dihapus dari daftar bookmark.');
    }
}
