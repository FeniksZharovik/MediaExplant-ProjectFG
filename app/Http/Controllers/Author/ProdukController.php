<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use App\Models\API\DeviceToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Author\Produk;

class ProdukController extends Controller
{
    protected NotificationService $notifier;

    public function __construct(NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

    public function create()
    {
        return view('author.create-product');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'judul'       => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'kategori'    => 'required|in:Buletin,Majalah',
            'media'       => 'required|file|mimes:pdf|max:10240',
            'cover'       => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'visibilitas' => 'required|in:public,private',
        ]);

        // 2. Validasi deskripsi tidak kosong setelah strip HTML
        $plainDesc = trim(strip_tags($request->deskripsi));
        if ($plainDesc === '' || $request->deskripsi === '<p><br></p>') {
            return redirect()->back()->withInput()->with('error', 'Deskripsi tidak boleh kosong.');
        }

        // 3. Ambil file media (PDF) sebagai binary
        $mediaFile    = $request->file('media');
        $mediaContent = file_get_contents($mediaFile->getRealPath());

        // 4. Ambil file cover (image) dan ubah ke base64 (untuk thumbnail/previews)
        $coverFile    = $request->file('cover');
        $coverContent = file_get_contents($coverFile->getRealPath());
        $coverBase64  = 'data:' . $coverFile->getMimeType() . ';base64,' . base64_encode($coverContent);

        // 5. Ambil UID user dari cookie
        $userUid = $request->cookie('user_uid');

        // 6. Generate ID acak untuk produk
        $produkId = Str::random(12);

        // 7. Simpan data produk ke tabel 'produk'
        try {
            DB::table('produk')->insert([
                'id'           => $produkId,
                'judul'        => $request->judul,
                'deskripsi'    => $request->deskripsi,
                'kategori'     => $request->kategori,
                'user_id'      => $userUid,
                'media'        => $mediaContent,
                'cover'        => $coverBase64,
                'release_date' => now(),
                'visibilitas'  => $request->visibilitas,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan produk', [
                'error'     => $e->getMessage(),
                'produk_id' => $produkId,
            ]);
            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan produk: ' . $e->getMessage());
        }

        // 8. Persiapkan hasil notifikasi
        $notificationResult = [];

        // 9. Kirim notifikasi hanya jika visibilitas adalah 'public'
        if ($request->visibilitas === 'public') {
            try {
                // Judul notifikasi: sama dengan judul produk
                $notifTitle = $request->judul;
                $plainDescNotif = strip_tags($request->deskripsi);
                $notifBody      = Str::limit($plainDescNotif, 50);
                $payloadData = [
                    'produk_id' => (string) $produkId,
                ];
                $notificationResult = $this->notifier->send($notifTitle, $notifBody, $payloadData);
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim notifikasi Produk', [
                    'error'     => $e->getMessage(),
                    'produk_id' => $produkId,
                ]);

                // Tetap lanjutkan — kembalikan array success=false
                $notificationResult = [
                    [
                        'success' => false,
                        'error'   => $e->getMessage(),
                    ]
                ];
            }
        } else {
            $notificationResult = [
                [
                    'success' => false,
                    'info'    => 'Visibilitas private, notifikasi tidak dikirim.'
                ]
            ];
        }

        // 10. Redirect kembali dengan pesan sukses, sertakan hasil notifikasi
        return redirect()
            ->back()
            ->with('success', 'Produk berhasil disimpan.')
            ->with('notificationResult', $notificationResult);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'deskripsi'   => 'required|string',
            'kategori'    => 'required|in:Buletin,Majalah',
            'media'       => 'nullable|file|mimes:pdf|max:10240',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'visibilitas' => 'required|in:public,private',
        ]);

        $plainDesc = trim(strip_tags($request->deskripsi));
        if ($plainDesc === '' || $request->deskripsi === '<p><br></p>') {
            return redirect()->back()->withInput()->with('error', 'Deskripsi tidak boleh kosong.');
        }

        $data = [
            'judul'       => $request->judul,
            'deskripsi'   => $request->deskripsi,
            'kategori'    => $request->kategori,
            'visibilitas' => $request->visibilitas,
        ];

        if ($request->hasFile('media')) {
            $data['media'] = file_get_contents($request->file('media')->getRealPath());
        }

        if ($request->hasFile('cover')) {
            $coverContent = file_get_contents($request->file('cover')->getRealPath());
            $data['cover'] = 'data:' . $request->file('cover')->getMimeType() . ';base64,' . base64_encode($coverContent);
        }

        DB::table('produk')->where('id', $id)->update($data);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function mediaPreview($id)
    {
        $produk = Produk::findOrFail($id);
        return response($produk->media)->header('Content-Type', 'application/pdf');
    }
}
