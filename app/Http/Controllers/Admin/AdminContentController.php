<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\API\Berita;
use App\Models\Author\Tag;
use App\Models\API\Karya;
use App\Models\API\Produk;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use App\Exports\BeritaExport;
use App\Models\API\Reaksi;
use Maatwebsite\Excel\Facades\Excel;

class AdminContentController extends Controller
{
    // ======================= Berita =======================
    public function berita(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $searchTerm = $request->input('search', '');
    
        $query = Berita::with('user');
    
        // 🔍 Search
        if ($searchTerm) {
            $query->where('judul', 'like', "%$searchTerm%")
                  ->orWhereHas('user', fn($q) => $q->where('nama_pengguna', 'like', "%$searchTerm%"));
        }
    
        // 📦 Filter
        if ($kategori = $request->input('kategori')) {
            $query->whereIn('kategori', $kategori);
        }
    
        if ($status = $request->input('status')) {
            $query->where('visibilitas', $status);
        }
    
        if ($tanggalDari = $request->input('tanggal_dari')) {
            $query->whereDate('tanggal_diterbitkan', '>=', $tanggalDari);
        }
    
        if ($tanggalSampai = $request->input('tanggal_sampai')) {
            $query->whereDate('tanggal_diterbitkan', '<=', $tanggalSampai);
        }
    
        // 🔁 Sorting
        if ($order = $request->input('order')) {
            if ($order === 'terbaru') {
                $query->orderByDesc('tanggal_diterbitkan'); // Pastikan kolom ini ada di database
            } elseif ($order === 'terpopuler') {
                $query->orderByDesc('view_count'); // Pastikan kolom ini ada di database
            }
        }
    
        // 📄 Pagination
        $beritas = $query->paginate($perPage)->appends([
            'search' => $searchTerm,
            'perPage' => $perPage,
            'kategori' => $request->input('kategori'),
            'order' => $request->input('order'),
            'tanggal_dari' => $request->input('tanggal_dari'),
            'tanggal_sampai' => $request->input('tanggal_sampai'),
            'status' => $request->input('status'),
        ]);
    
        $tags = Tag::all();
        return view('dashboard-admin.menu.berita.berita', compact('beritas', 'tags', 'perPage', 'searchTerm'));
    }

    public function detailBerita($id)
    {
        $beritas = Berita::with(['user', 'tags', 'reaksis'])->findOrFail($id);
        $tags = Tag::all();
        $users = User::all();
    
        $likeCount = Reaksi::where('item_id', $beritas->id)
            ->where('jenis_reaksi', 'Suka')
            ->where('reaksi_type', 'Berita') // perbaiki ini
            ->count();

        $dislikeCount = Reaksi::where('item_id', $beritas->id)
            ->where('jenis_reaksi', 'Tidak Suka')
            ->where('reaksi_type', 'Berita') // perbaiki ini
            ->count();

        return view('dashboard-admin.menu.berita._detail', compact('beritas', 'tags', 'users', 'likeCount', 'dislikeCount'));
    }

    public function delete(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();

        return redirect()
            ->route('admin.berita')
            ->with('success', 'Berita berhasil dihapus');
    }

    // public function exportBerita() 
    // {
    //     return Excel::download(new BeritaExport, 'berita.xlsx');
    // }

    // ======================= Produk =======================
    public function produk(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $searchTerm = $request->input('search', '');

        $query = Produk::with('user'); // Pastikan relasi 'user' ada di model Produk

        // 🔍 Search
        if ($searchTerm) {
            $query->where('judul', 'like', "%$searchTerm%")
                ->orWhereHas('user', fn($q) => $q->where('nama_pengguna', 'like', "%$searchTerm%"));
        }

        // 📦 Filter
        if ($kategori = $request->input('kategori')) {
            $query->whereIn('kategori', $kategori);
        }

        if ($status = $request->input('status')) {
            $query->where('visibilitas', $status);
        }

        if ($tanggalDari = $request->input('tanggal_dari')) {
            $query->whereDate('release_date', '>=', $tanggalDari);
        }

        if ($tanggalSampai = $request->input('tanggal_sampai')) {
            $query->whereDate('release_date', '<=', $tanggalSampai);
        }

        // 🔁 Sorting
        if ($order = $request->input('order')) {
            if ($order === 'terbaru') {
                $query->orderByDesc('release_date');
            } elseif ($order === 'terlama') {
                $query->orderBy('release_date');
            }
        }

        // 📄 Pagination (hapus 'tags' & 'users' dari appends karena tidak digunakan)
        $produks = $query->paginate($perPage)->appends([
            'search' => $searchTerm,
            'perPage' => $perPage,
            'kategori' => $request->input('kategori'),
            'order' => $request->input('order'),
            'tanggal_dari' => $request->input('tanggal_dari'),
            'tanggal_sampai' => $request->input('tanggal_sampai'),
            'status' => $request->input('status'),
        ]);

        // ✅ Hapus jika tidak digunakan
        // $tags = Tag::all(); // Tidak digunakan, bisa dihapus
        $users = User::all(); // Jika digunakan di view, biarkan

        return view('dashboard-admin.menu.produk.produk', compact('produks', 'users', 'perPage', 'searchTerm'));
    }

    public function detailProduk(Request $request, $id)
    {
        $produk = Produk::with(['user', 'reaksis', 'tags'])->findOrFail($id);
        $users = User::all();
        $tags = Tag::all(); 

        $likeCount = Reaksi::where('item_id', $produk->id)
        ->where('jenis_reaksi', 'Suka')
        ->where('reaksi_type', 'Produk') 
        ->count();

        $dislikeCount = Reaksi::where('item_id', $produk->id)
            ->where('jenis_reaksi', 'Tidak Suka')
            ->where('reaksi_type', 'Produk') 
            ->count();

        return view('dashboard-admin.menu.produk.detail', compact('produk', 'users', 'tags', 'likeCount', 'dislikeCount'));
    }

    public function deleteProduk(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()
            ->route('admin.produk')
            ->with('success', 'Produk berhasil dihapus');
    }

    public function pdfPreview($id)
    {
        $produk = Produk::findOrFail($id);
    
        // Ensure media exists and is accessible
        if (!$produk->media) {
            return abort(404, "PDF tidak ditemukan.");
        }
    
        // If media is stored as a file path (e.g., in storage/public)
        if (file_exists($produk->media)) {
            return response()->file($produk->media, [
                'Content-Type' => 'application/pdf',
            ]);
        }
    
        // If media is stored as binary data (e.g., BLOB in DB)
        return response($produk->media, 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadPdf($id)
    {
        $majalah = Produk::findOrFail($id);

        if (!$majalah || !$majalah->media) {
            return abort(404, "PDF tidak ditemukan.");
        }

        $filename = str_replace(' ', '_', $majalah->judul) . '.pdf';

        return Response::make($majalah->media, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    // ======================= Karya =======================
    /**
     * Menampilkan daftar karya dengan filter, pencarian, dan pagination
     */ 

    public function karya(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $searchTerm = $request->input('search', '');
    
        $query = Karya::with('user');
    
        // 🔍 Search
        if ($searchTerm) {
            $query->where('judul', 'like', "%$searchTerm%")
                  ->orWhereHas('user', fn($q) => $q->where('nama_pengguna', 'like', "%$searchTerm%"));
        }
    
        // 📦 Filter categories
        $availableCategories = ['puisi', 'pantun', 'syair', 'fotografi', 'desain grafis'];
        
        if ($kategori = $request->input('kategori')) {
            $query->whereIn('kategori', $kategori);
        }
    
        // Status filter (assuming visibility field exists)
        if ($status = $request->input('status')) {
            $query->where('visibilitas', $status);
        }
    
        // Date range filter
        if ($tanggalDari = $request->input('tanggal_dari')) {
            $query->whereDate('tanggal_diterbitkan', '>=', $tanggalDari);
        }
    
        if ($tanggalSampai = $request->input('tanggal_sampai')) {
            $query->whereDate('tanggal_diterbitkan', '<=', $tanggalSampai);
        }
    
        // 🔁 Sorting
        if ($order = $request->input('order')) {
            if ($order === 'terbaru') {
                $query->orderByDesc('tanggal_diterbitkan');
            } elseif ($order === 'terpopuler') {
                $query->orderByDesc('view_count'); 
            }
        }
    
        // 📄 Pagination
        $karyas = $query->paginate($perPage)->appends([
            'search' => $searchTerm,
            'perPage' => $perPage,
            'kategori' => $request->input('kategori'),
            'order' => $request->input('order'),
            'tanggal_dari' => $request->input('tanggal_dari'),
            'tanggal_sampai' => $request->input('tanggal_sampai'),
            'status' => $request->input('status'),
        ]);
    
        return view('dashboard-admin.menu.karya.karya', compact(
            'karyas', 
            'perPage', 
            'searchTerm',
            'availableCategories'
        ));
    }

    public function detailKarya($id)
    {
        $karya = Karya::with(['user', 'tags', 'reaksis'])->findOrFail($id);
        $users = User::all();
        $tags = Tag::all(); 

        $likeCount = Reaksi::where('item_id', $karya->id)
        ->where('jenis_reaksi', 'Suka')
        ->where('reaksi_type', 'Karya') 
        ->count();

        $dislikeCount = Reaksi::where('item_id', $karya->id)
            ->where('jenis_reaksi', 'Tidak Suka')
            ->where('reaksi_type', 'Karya') 
            ->count();

        return view('dashboard-admin.menu.karya.detail', compact('karya','users','tags','likeCount','dislikeCount'));
    }    

    public function deleteKarya(Request $request, $id)
    {
        $karya = Karya::findOrFail($id);
        $karya->delete();

        return redirect()
            ->route('admin.karya')
            ->with('success', 'Produk berhasil dihapus');
    }    
}