<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\StrukturDivisi;
use App\Models\Admin\StrukturAnggota;
use App\Models\Admin\TentangKami;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StrukturOrganisasiController extends Controller
{
    public function index(Request $request)
    {        
        $perPage = $request->input('perPage', 10);
        $searchTerm = $request->input('search', '');
        $selectedDivisi = $request->input('divisi', 'semua');

        // Get all divisions for filter dropdown
        $divisis = StrukturDivisi::pluck('nama_divisi', 'id');
        $users = User::all();
        
        $divisiTable = DB::table('organisasi_divisi')
            ->select(
                'organisasi_divisi.id',
                'organisasi_divisi.nama_divisi',
                'organisasi_divisi.row',
                DB::raw('(
                    SELECT COUNT(*) 
                    FROM organisasi_anggota 
                    WHERE organisasi_anggota.id_divisi = organisasi_divisi.id
                ) as total_anggota')
            )
            ->orderBy('organisasi_divisi.row')
            ->get();

        // Build query with relationships
        $query = StrukturAnggota::with(['divisi', 'user'])
            ->when($selectedDivisi !== 'semua', function ($q) use ($selectedDivisi) {
                $q->where('id_divisi', $selectedDivisi);
            });

        // 🔍 Enhanced search: across user.nama_pengguna, title_perangkat, AND divisi.nama_divisi
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($q2) use ($searchTerm) {
                        $q2->where('nama_pengguna', 'like', "%$searchTerm%");
                    })
                    ->orWhere('title_perangkat', 'like', "%$searchTerm%")
                    ->orWhereHas('divisi', function ($q2) use ($searchTerm) {
                        $q2->where('nama_divisi', 'like', "%$searchTerm%");
                    });
            });
        }

        // Paginate and preserve filters
        $anggotas = $query->paginate($perPage)->appends([
            'search' => $searchTerm,
            'perPage' => $perPage,
            'divisi' => $selectedDivisi,
        ]);

        return view('dashboard-admin.menu.menuOrganisasi', compact(
            'anggotas',
            'users',
            'divisis',
            'perPage',
            'searchTerm',
            'selectedDivisi',
            'divisiTable',            
        ));
    }
    
    public function strukturOrganisasi()
    {
        // Fetch divisions with their members and users
        $DeskripsiOrganisasi = TentangKami::first()?->fokus_utama;
        $divisis = StrukturDivisi::with(['anggotas.user'])
        ->orderBy('row')->get();
    
        return view('header-footer.footer-menu.strukturOrganisasi', compact('divisis', 'DeskripsiOrganisasi'));
    }

    public function tentangKami()
    {
        $tentangKamiDeskripsi = TentangKami::first()?->tentangKami;
        
        return view('header-footer.footer-menu.tentangKami', compact('tentangKamiDeskripsi'));
    }

    public function kodeEtik()
    {
        $kodeEtikDeskripsi = TentangKami::first()?->kodeEtik;
        
        return view('header-footer.footer-menu.kode-etik', compact('kodeEtikDeskripsi'));
    }
    
    public function explantContributor()
    {
        $explantContributorDeskripsi = TentangKami::first()?->explantContributor;
        
        return view('header-footer.footer-menu.explantContributor', compact('explantContributorDeskripsi'));
    }       

    public function storeDivisi(Request $request)
    {
        $validated = $request->validate([
            'nama_divisi' => 'required|max:50',
            'row' => 'required|integer|min:1',
        ]);
    
        $validated['id'] = (string) Str::uuid();
    
        $divisi = StrukturDivisi::create($validated);
    
        return response()->json([
            'success' => 'Divisi berhasil ditambahkan!',
            'data' => $divisi
        ]);
    }

    public function destroyDivisi($id)
    {
        $divisi = StrukturDivisi::findOrFail($id);
        $divisi->delete();

        return redirect()
        ->route('admin.organisasi.index') 
        ->with('success', 'Anggota berhasil dihapus!');
    }

    public function footer()
    {
        // Fetch divisions with their members and users
        $DeskripsiOrganisasi = TentangKami::All();
    }
    // try {
        //     $divisi = StrukturDivisi::findOrFail($id);

        //     if ($divisi->anggotas()->count() > 0) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Tidak dapat menghapus divisi yang masih memiliki anggota!'
        //         ], 400);
        //     }

        //     $divisi->delete();

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Divisi berhasil dihapus!',
        //         'redirect' => route('admin.organisasi.index')
        //     ]);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        //     ], 500);
        // }
    public function createAnggota(Request $request)
    {
        // Validate input, ensure user exists and is not already a member
        $validated = $request->validate([
            'title_perangkat' => 'required|max:20',
            'id_divisi'       => 'required|exists:organisasi_divisi,id',
            'uid'             => ['required', 'exists:user,uid', 'unique:organisasi_anggota,uid'],
        ], [
            'uid.exists' => 'Pengguna tidak ditemukan.',
            'uid.unique' => 'Pengguna ini sudah terdaftar sebagai anggota.',
        ]);

        // Generate a UUID for the primary key
        $validated['id'] = (string) Str::uuid();

        // Create the anggota record
        StrukturAnggota::create($validated);

        return response()->json(['success' => 'Anggota berhasil ditambahkan!']);
    }
    
    public function updateAnggota(Request $request, $id)
    {
        $anggota = StrukturAnggota::findOrFail($id);

        $validated = $request->validate([
            'title_perangkat' => 'required|max:30',
            'id_divisi'       => 'required|exists:organisasi_divisi,id',
            'uid'             => [
                'required',
                'exists:user,uid',
                // exclude current record from unique check
                \Illuminate\Validation\Rule::unique('organisasi_anggota', 'uid')->ignore($anggota->uid, 'uid'),
            ],
        ], [
            'uid.exists' => 'Pengguna tidak ditemukan.',
            'uid.unique' => 'Pengguna ini sudah terdaftar sebagai anggota lain.',
        ]);

        $anggota->update($validated);

        return response()->json(['success' => 'Anggota berhasil diupdate!']);
    }

    public function destroyAnggota(Request $request, $id)
    {
        $anggota = StrukturAnggota::findOrFail($id);
        $anggota->delete();

        return redirect()
        ->route('admin.organisasi.index') // Ganti dengan nama rute sebenarnya
        ->with('success', 'Anggota berhasil dihapus!');
    }
}