<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pesan', function (Blueprint $table) {
            // Drop foreign keys kalau ada
            foreach (['produk_id', 'karya_id', 'berita_id', 'komen_id'] as $col) {
                if (Schema::hasColumn('pesan', $col)) {
                    try {
                        $table->dropForeign([$col]);
                    } catch (\Throwable $e) {
                        // abaikan error kalau foreign key tidak ada
                    }
                }
            }

            // Drop kolom lama
            $drop = [];
            foreach (['produk_id', 'karya_id', 'berita_id', 'komen_id'] as $col) {
                if (Schema::hasColumn('pesan', $col)) {
                    $drop[] = $col;
                }
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }

            // Tambahkan kolom baru jika belum ada
            if (!Schema::hasColumn('pesan', 'pesan_type')) {
                $table->string('pesan_type');
            }

            if (!Schema::hasColumn('pesan', 'item_id')) {
                $table->string('item_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pesan', function (Blueprint $table) {
            // Kembalikan kolom lama
            foreach (['produk_id', 'karya_id', 'berita_id', 'komen_id'] as $col) {
                if (!Schema::hasColumn('pesan', $col)) {
                    $table->string($col)->nullable();
                }
            }

            // Hapus kolom polymorphic
            $drop = [];
            foreach (['pesan_type', 'item_id'] as $col) {
                if (Schema::hasColumn('pesan', $col)) {
                    $drop[] = $col;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
