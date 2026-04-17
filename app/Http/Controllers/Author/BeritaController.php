<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Author\Berita;
use App\Models\Author\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\DeviceToken;
use App\Providers\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class BeritaController extends Controller
{

    protected NotificationService $notifier;

    public function __construct(NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

    public function store(Request $request)
    {
        // 1. Validasi input (tags tidak wajib)
        $request->validate([
            'judul'            => 'required|max:100',
            'konten_berita'    => 'required|string',
            'kategori'         => 'required',
            'visibilitas'      => 'required|in:public,private',
        ]);

        // 2. Validasi tambahan: pastikan konten bukan hanya kosong atau <p><br></p>
        $kontenBersih = trim(strip_tags($request->konten_berita));
        if ($kontenBersih === '' || $request->konten_berita === '<p><br></p>') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Konten berita tidak boleh kosong.');
        }

        // 3. Ambil user UID dari cookie
        $userUid = $request->cookie('user_uid');

        // 4. Buat ID berita secara acak dan simpan artikel
        $articleId = Str::random(12);
        $article = Berita::create([
            'id'                  => $articleId,
            'judul'               => $request->judul,
            'tanggal_diterbitkan' => $request->tanggal_diterbitkan,
            'user_id'             => $userUid,
            'kategori'            => $request->kategori,
            'konten_berita'       => $request->konten_berita,
            'visibilitas'         => $request->visibilitas,
        ]);

        // 5. Simpan tags jika ada
        if ($request->has('tags') && !empty($request->tags)) {
            $tags = explode(',', $request->tags);
            foreach ($tags as $tag) {
                Tag::create([
                    'id'        => Str::random(12),
                    'nama_tag'  => trim($tag),
                    'berita_id' => $articleId,
                ]);
            }
        }

        $notificationResult = [];

        // 6. Hanya kirim notifikasi jika visibilitas adalah 'public'
        if ($article->visibilitas === 'public') {
            try {
                $notifTitle = $article->judul;
                $notifBody  = Str::limit(strip_tags($article->konten_berita), 50);
                $payloadData = [
                    'berita_id' => (string) $article->id,
                ];

                $notificationResult = $this->notifier->send($notifTitle, $notifBody, $payloadData);
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim notifikasi berita', [
                    'error'     => $e->getMessage(),
                    'berita_id' => $article->id,
                ]);

                $notificationResult = [
                    [
                        'success' => false,
                        'error'   => $e->getMessage(),
                    ]
                ];
            }
        } else {
            // Jika private, lewati pengiriman notifikasi
            $notificationResult = [
                [
                    'success' => false,
                    'info'    => 'Visibilitas private, notifikasi tidak dikirim.'
                ]
            ];
        }

        // 7. Redirect kembali dengan pesan sukses, sertakan hasil notifikasi (opsional)
        return redirect()
            ->back()
            ->with('success', 'Berita berhasil dipublikasikan.')
            ->with('notificationResult', $notificationResult);
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi input
        $request->validate([
            'judul'         => 'required|max:100',
            'kategori'      => 'required',
            'visibilitas'   => 'required|in:public,private',
            'konten_berita' => 'required|string',
        ]);

        $kontenBersih = trim(strip_tags($request->konten_berita));
        if ($kontenBersih === '' || $request->konten_berita === '<p><br></p>') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Konten berita tidak boleh kosong.');
        }

        // 2. Temukan dan update berita + relasi tags (INI yang diperbaiki)
        $berita = Berita::with('tags')->findOrFail($id); // ✅ WAJIB eager load 'tags'

        $berita->judul = $request->judul;
        $berita->kategori = $request->kategori;
        $berita->visibilitas = $request->visibilitas;
        $berita->konten_berita = $request->konten_berita;
        $berita->save();

        // 3. Perbarui tag jika ada
        if ($request->filled('tags')) {
            $tags = explode(',', $request->tags);
            $berita->tags()->delete();

            foreach ($tags as $tag) {
                $berita->tags()->create([
                    'id' => Str::random(12),
                    'nama_tag' => trim($tag),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Berita berhasil diperbarui.');
    }

    public function edit($id)
    {
        $berita = Berita::with('tags')->findOrFail($id);
        return view('authors.edit.create-edit', compact('berita'));
    }
}
