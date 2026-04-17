<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile\Liked;
use App\Models\Produk\Buletin;
use App\Models\Produk\Majalah;
use App\Models\Karya\Karya;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class LikedController extends Controller
{
    public function index(Request $request)
    {
        $user = Session::get('user');
        Carbon::setLocale('id');

        $query = Liked::with(['berita', 'karya'])
            ->where('jenis_reaksi', 'Suka')
            ->where('user_id', $user->uid);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = str_replace('_', ' ', strtolower($request->search));

            $query->where(function ($q) use ($search) {
                // Pencarian Berita
                $q->where(function ($q1) use ($search) {
                    $q1->where('reaksi_type', 'Berita')
                        ->whereHas('berita', function ($q2) use ($search) {
                            $q2->whereRaw('LOWER(REPLACE(kategori, "_", " ")) like ?', ["%{$search}%"])
                                ->orWhereRaw('LOWER(judul) like ?', ["%{$search}%"]);
                        });
                })
                    // Pencarian Karya
                    ->orWhere(function ($q3) use ($search) {
                        $q3->where('reaksi_type', 'Karya')
                            ->whereHas('karya', function ($q4) use ($search) {
                                $q4->whereRaw('LOWER(REPLACE(kategori, "_", " ")) like ?', ["%{$search}%"])
                                    ->orWhereRaw('LOWER(judul) like ?', ["%{$search}%"]);
                            });
                    })
                    // Pencarian Produk (Buletin dan Majalah)
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

                        $q5->where('reaksi_type', 'Produk')
                            ->whereIn('item_id', $allIds);
                    });
            });
        }

        // Sorting
        $query->orderBy('tanggal_reaksi', $request->sort === 'asc' ? 'asc' : 'desc');

        $likedRaw = $query->paginate(50);
        $likedRaw->appends($request->query());

        $likedItems = collect($likedRaw->items())->filter(function ($liked) {
            switch ($liked->reaksi_type) {
                case 'Berita':
                    return $liked->berita && $liked->berita->visibilitas === 'public';
                case 'Karya':
                    return $liked->karya && $liked->karya->visibilitas === 'public';
                case 'Produk':
                    $produk = $this->getProduk($liked->item_id);
                    return $produk && $produk->visibilitas === 'public';
                default:
                    return false;
            }
        })->map(function ($liked) {
            switch ($liked->reaksi_type) {
                case 'Berita':
                    $item = $liked->berita;
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
                    $item = $liked->karya;
                    $thumbnail = 'data:image/jpeg;base64,' . $item->media;
                    $kategoriName = strtoupper(str_replace('_', ' ', $item->kategori));
                    $kategoriSlug = str_replace('_', '-', strtolower($item->kategori));
                    $judul = $item->judul;
                    $url = url("/karya/{$kategoriSlug}/read?k={$item->id}");
                    break;

                case 'Produk':
                    $item = $this->getProduk($liked->item_id);
                    $thumbnail = $item->cover ?? asset('images/default-thumbnail.jpg');
                    $kategoriName = strtoupper(str_replace('_', ' ', $item->kategori ?? 'Produk'));
                    $judul = $item->judul;
                    $url = url("/produk/" . strtolower($item->kategori ?? 'produk') . "/browse?f={$item->id}");
                    break;

                default:
                    return null;
            }

            return [
                'id' => $liked->item_id,
                'judul' => $judul,
                'kategori' => $kategoriName,
                'thumbnail' => $thumbnail,
                'tanggal_disukai' => $liked->tanggal_reaksi,
                'disukai_ago' => Carbon::parse($liked->tanggal_reaksi)->diffForHumans(),
                'url' => $url,
            ];
        })->filter();

        // Replace collection on paginator
        $likedRaw = new \Illuminate\Pagination\LengthAwarePaginator(
            $likedItems->values(),
            $likedRaw->total(),
            $likedRaw->perPage(),
            $likedRaw->currentPage(),
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('profile.liked', ['likedItems' => $likedRaw]);
    }

    protected function getProduk($id)
    {
        return Buletin::find($id) ?? Majalah::find($id);
    }

    public function destroy($id)
    {
        $user = Session::get('user');

        $liked = Liked::where('user_id', $user->uid)
            ->where('item_id', $id)
            ->where('jenis_reaksi', 'Suka')
            ->firstOrFail();

        $liked->delete();

        return redirect()->route('liked')->with('success', 'Berhasil dihapus dari daftar disukai.');
    }
}
