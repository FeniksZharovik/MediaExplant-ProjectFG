<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\API\karya;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KaryaController extends Controller
{

    public function getDetailKarya(Request $request)
    {
        $id = $request->query('karya_id');
        $userId = $request->query('user_id');

        if (!$id) {
            return response()->json(['message' => 'ID karya wajib disertakan.'], 400);
        }

        $karya = Karya::with(['bookmarks', 'reaksis', 'komentars', 'user'])
            ->where('id', $id)
            ->first();

        if (!$karya) {
            return response()->json(['message' => 'Karya tidak ditemukan.'], 404);
        }

        $tanggalDiterbitkan = Carbon::parse($karya->release_date);
        $profilePic = !empty($karya->user->profile_pic) ? base64_encode($karya->user->profile_pic) : null;

        $response = [
            'idKarya' => $karya->id,
            'penulis' => $karya->user->nama_lengkap ?? null,
            'profil' => $profilePic,
            'krator' => $karya->creator,
            'judul' => $karya->judul,
            'deskripsi' => $karya->deskripsi,
            'kontenKarya' => $karya->konten,
            'media' => $karya->media,
            'visibilitas' => $karya->visibilitas,
            'kategori' => $karya->kategori,
            'release' => $tanggalDiterbitkan->toDateTimeString(),
            'jumlahLike' => $karya->reaksis->where('jenis_reaksi', 'Suka')->count(),
            'jumlahDislike' => $karya->reaksis->where('jenis_reaksi', 'Tidak Suka')->count(),
            'jumlahKomentar' => $karya->komentars->count(),
            'isBookmark' => $userId ? $karya->bookmarks->where('user_id', $userId)->count() > 0 : false,
            'isLike' => $userId ? $karya->reaksis->where('user_id', $userId)->where('jenis_reaksi', 'Suka')->count() > 0 : false,
            'isDislike' => $userId ? $karya->reaksis->where('user_id', $userId)->where('jenis_reaksi', 'Tidak Suka')->count() > 0 : false,
        ];

        return response()->json($response);
    }

    public function getkaryaRekomendasi(Request $request)
    {
        $userId = $request->query('user_id');

        // Ambil kategori karya yang pernah dibookmark user (jika user login)
        $kategoriFavorit = collect();

        if ($userId) {
            $kategoriFavorit = Karya::whereHas('bookmarks', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->pluck('kategori')->unique();
        }

        // Jika ada kategori favorit, ambil karya berdasarkan kategori tersebut
        if ($kategoriFavorit->isNotEmpty()) {
            $karyas = Karya::with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
                ->whereIn('kategori', $kategoriFavorit)
                ->inRandomOrder()
                ->paginate(10);
        } else {
            // Jika tidak ada (user belum login atau belum pernah bookmark), ambil berdasarkan view & like
            $karyas = Karya::withCount([
                'reaksis as like_count' => function ($q) {
                    $q->where('jenis_reaksi', 'Suka');
                }
            ])
                ->orderByDesc('view_count')
                ->orderByDesc('like_count')
                ->with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
                ->paginate(10);
        }

        return response()->json($this->formatKaryaResponse($karyas, $userId));
    }


    // Mengambil karya terbaru
    public function getPuisiTerbaru(Request $request)
    {
        $userId = $request->query('user_id', null);

        $karyas = Karya::with(['user'])
            ->where('kategori', 'puisi')
            ->where('visibilitas', 'public')
            ->orderByDesc('release_date')
            ->paginate(5);
        return response()->json($this->formatKaryaResponse($karyas, $userId));
    }
    // Mengambil syair terbaru
    public function getSyairTerbaru(Request $request)
    {
        $userId = $request->query('user_id', null);

        $karyas = Karya::with(['user'])
            ->where('kategori', 'syair')
            ->where('visibilitas', 'public')
            ->orderByDesc('release_date')
            ->paginate(5);
        return response()->json($this->formatKaryaResponse($karyas, $userId));
    }

    // Mengambil desain grafis terbaru
    public function getDesainGrafisTerbaru(Request $request)
    {
        $userId = $request->query('user_id', null);

        $karyas = Karya::with(['user'])
            ->where('kategori', 'desain_grafis')
            ->where('visibilitas', 'public')
            ->orderByDesc('release_date')
            ->paginate(5);
        return response()->json($this->formatKaryaResponse($karyas, $userId));
    }
    // Mengambil desain grafis terbaru
    public function getFotografiTerbaru(Request $request)
    {
        $userId = $request->query('user_id', null);

        $karyas = Karya::with(['user'])
            ->where('kategori', 'fotografi')
            ->where('visibilitas', 'public')
            ->orderByDesc('release_date')
            ->paginate(5);
        // ->paginate(5);
        return response()->json($this->formatKaryaResponse($karyas, $userId));
    }

    // Mengambil karya populer (berdasarkan jumlah "like".
    public function getPantunPopuler(Request $request)
    {
        $userId = $request->query('user_id', null);


        $karyas = Karya::withCount(['reaksis as jumlah_like' => function ($q) {
            $q->where('jenis_reaksi', 'Suka');
        }])
            ->with(['tags', 'bookmarks', 'reaksis', 'komentars', 'user'])
            ->orderByDesc('jumlah_like')
            ->orderByDesc('view_count')
            ->limit(5)
            ->get();
        return response()->json($this->formatKaryaResponse($karyas, $userId));
    }

    private function formatKaryaResponse($karyas, $userId)
    {
        return $karyas->map(function ($karya) use ($userId) {
            $tanggalDiterbitkan = Carbon::parse($karya->release_date);
            // $profilePic = !empty($karya->user->profile_pic) ? base64_encode($karya->user->profile_pic) : null;

            return [
                'idKarya' => $karya->id,
                'judul' => $karya->judul,
                'media' => $karya->media,
                'kategori' => $karya->kategori,
                'release' => $tanggalDiterbitkan->toDateTimeString(),
            ];
        });
    }
}

// php artisan serve --host=0.0.0.0 --port=8000
