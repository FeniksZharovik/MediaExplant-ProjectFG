<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\Pesan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KotakMasukController extends Controller
{
    public function index(Request $request)
{
    $searchTerm = $request->input('search', '');
    $perPage = $request->input('perPage', 150);
    $filter = $request->input('filter');

    $query = Pesan::with('user');

    // 🔍 Apply filter
    if ($filter) {
        match ($filter) {
            'masukan' => $query->where('status', 'masukan'),
            'laporan' => $query->where('status', 'laporan'),
            'showAll' => $query = Pesan::with('user'),
            'starred' => $query->where('star', 'iya'),
            'terbaru' => $query->whereDate('created_at', now()),
            default => null,
        };
    }

    // 🔍 Apply search
    if ($searchTerm) {
        $query->where(function ($q) use ($searchTerm) {
            $q->where('pesan', 'like', "%$searchTerm%")
              ->orWhere('detail_pesan', 'like', "%$searchTerm%")
              ->orWhere('nama', 'like', "%$searchTerm%")
              ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                  $userQuery->where('nama_pengguna', 'like', "%$searchTerm%");
              });
        });
    }

    // 📄 Paginate with filters
    $pesans = $query->paginate($perPage)->appends([
        'search' => $searchTerm,
        'filter' => $filter
    ]);

    return view('dashboard-admin.menu.kotak_masuk', compact('pesans'));
}

    // Detail pesan
    public function show($id)
    {
        $pesan = Pesan::with('user')->find($id);
        if (!$pesan) abort(404);

        return view('dashboard-admin.menu.detail_kotak_masuk', compact('pesan'));
    }

    // Hapus pesan secara bulk
    public function destroy(Request $request)
    {
        $ids = $request->input('message_ids');

        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'Tidak ada pesan yang dipilih.');
        }

        Pesan::whereIn('id', $ids)->delete();

        return back()->with('success', 'Pesan berhasil dihapus.');
    }

    // Toggle bintang
    public function toggleStar($id)
    {
        $pesan = Pesan::find($id);
        if ($pesan) {
            $pesan->star = $pesan->star === 'iya' ? 'tidak' : 'iya';
            $pesan->save();
        }
        return response()->json(['status' => 'success']);
    }
}
