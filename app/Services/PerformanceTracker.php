<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PerformanceTracker
{
    /**
     * Track aktivitas pengguna dan update performa_kategori_harian
     *
     * @param string $tipeKonten (berita, produk, karya)
     * @param string $kategori
     * @param string $jenisAktivitas (view, like, dislike)
     */
    public static function track($tipeKonten, $kategori, $jenisAktivitas)
    {
        $today = now()->toDateString();

        // Tentukan kolom mana yang harus di-update
        $columnToUpdate = match ($jenisAktivitas) {
            'view' => 'view_count',
            'like' => 'like_count',
            'dislike' => 'dislike_count',
            default => null,
        };

        if (!$columnToUpdate) return;

        // Gunakan upsert() untuk insert atau update
        DB::table('performa_kategori_harian')->upsert(
            [
                'datetime' => $today,
                'tipe_konten' => $tipeKonten,
                'kategori' => $kategori,
                $columnToUpdate => 1
            ],
            ['datetime', 'tipe_konten', 'kategori'],
            [$columnToUpdate]
        );
    }
}