<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author\Karya;
use App\Models\API\DeviceToken;
use App\Providers\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KaryaController extends Controller
{
    protected NotificationService $notifier;

    public function __construct(NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (mirip persis dengan BeritaController, hanya sesuaikan rule-nya)
        $request->validate([
            'penulis'     => 'required|string|max:100',
            'judul'       => 'required|string|max:150',
            'deskripsi'   => 'required_unless:kategori,fotografi,desain_grafis|string',
            'konten'      => 'nullable|string',
            'kategori'    => 'required|string',
            'media'       => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'visibilitas' => 'required|in:public,private',
        ]);

        // 2. Validasi deskripsi secara manual (tidak boleh kosong setelah strip HTML)
        $plainDesc = trim(strip_tags($request->deskripsi));
        if ($plainDesc === '' || $request->deskripsi === '<p><br></p>') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Deskripsi tidak boleh kosong.');
        }

        // 3. Validasi konten wajib jika kategori adalah puisi, pantun, atau syair
        $kategoriTeks = ['puisi', 'pantun', 'syair'];
        if (in_array($request->kategori, $kategoriTeks)) {
            if (trim($request->konten) === '') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Konten tidak boleh kosong untuk kategori teks.');
            }
        }

        // 4. Konversi file gambar menjadi base64 (jika ada)
        $fileBase64 = null;
        if ($request->hasFile('media')) {
            $fileBase64 = base64_encode(
                file_get_contents($request->file('media')->path())
            );
        }

        // 5. Ambil uid pengguna dari cookie (sama seperti BeritaController)
        $userUid = $request->cookie('user_uid');

        // 6. Generate ID acak untuk Karya
        $karyaId = Str::random(12);

        // 7. Simpan ke Database
        $karya = Karya::create([
            'id'           => $karyaId,
            'creator'      => $request->penulis,
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi ?? '',
            'konten'       => $request->konten ?? '',
            'kategori'     => $request->kategori,
            'user_id'      => $userUid,
            'media'        => $fileBase64,
            'release_date' => now(),
            'visibilitas'  => $request->visibilitas,
        ]);

        // 8. Kirim notifikasi hanya jika visibilitas adalah 'public'
        $notificationResult = [];
        if ($karya->visibilitas === 'public') {
            try {
                $notifTitle = $karya->judul;

                $plainDescNotif = strip_tags($karya->deskripsi);
                $notifBody      = Str::limit($plainDescNotif, 50);

                $payloadData = [
                    'karya_id' => (string) $karya->id,
                ];

                $notificationResult = $this->notifier->send($notifTitle, $notifBody, $payloadData);
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim notifikasi Karya', [
                    'error'    => $e->getMessage(),
                    'karya_id' => $karya->id,
                ]);

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

        // 9. Redirect kembali dengan pesan sukses, sertakan hasil notifikasi
        return redirect()
            ->back()
            ->with('success', 'Karya berhasil dipublikasikan!')
            ->with('notificationResult', $notificationResult);
    }

    public function update(Request $request, $id)
    {
        // Validasi input (bisa sesuaikan seperti di method store)
        $request->validate([
            'penulis'     => 'required|string|max:100',
            'judul'       => 'required|string|max:150',
            'deskripsi'   => 'required_unless:kategori,fotografi,desain_grafis|string',
            'konten'      => 'nullable|string',
            'media'       => 'nullable|file|mimes:jpg,jpeg,png|max:10240',
            'kategori'    => 'required|string',
            'visibilitas' => 'required|in:public,private',
        ]);

        $plainDesc = trim(strip_tags($request->deskripsi));
        if ($plainDesc === '' || $request->deskripsi === '<p><br></p>') {
            return redirect()->back()->withInput()->with('error', 'Deskripsi tidak boleh kosong.');
        }

        // Cek apakah Karya dengan ID tersebut ada
        $karya = Karya::findOrFail($id);

        // Konversi file gambar menjadi base64 jika ada file baru
        if ($request->hasFile('media')) {
            $fileBase64 = base64_encode(
                file_get_contents($request->file('media')->path())
            );
            $karya->media = $fileBase64;
        }

        // Update data
        $karya->update([
            'creator'     => $request->penulis,
            'judul'       => $request->judul,
            'deskripsi'   => $request->deskripsi ?? '',
            'konten'      => $request->konten ?? '',
            'kategori'    => $request->kategori,
            'media'       => $karya->media,
            'visibilitas' => $request->visibilitas,
        ]);

        // Redirect
        return redirect()
            ->back()
            ->with('success', 'Karya berhasil diperbarui.');
    }
}
