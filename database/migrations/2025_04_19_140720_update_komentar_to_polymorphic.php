<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('komentar', function (Blueprint $table) {
            // Drop foreign keys terlebih dahulu jika ada
            if (Schema::hasColumn('komentar', 'berita_id')) {
                $table->dropForeign(['berita_id']);
            }

            if (Schema::hasColumn('komentar', 'produk_id')) {
                $table->dropForeign(['produk_id']);
            }

            if (Schema::hasColumn('komentar', 'karya_id')) {
                $table->dropForeign(['karya_id']);
            }

            // Drop kolom lama
            $drop = [];
            foreach (['berita_id', 'produk_id', 'karya_id'] as $col) {
                if (Schema::hasColumn('komentar', $col)) {
                    $drop[] = $col;
                }
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }

            // Tambahkan kolom polymorphic jika belum ada
            if (!Schema::hasColumn('komentar', 'komentar_type')) {
                $table->string('komentar_type');
            }

            if (!Schema::hasColumn('komentar', 'item_id')) {
                $table->string('item_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('komentar', function (Blueprint $table) {
            // Tambah kembali kolom lama
            if (!Schema::hasColumn('komentar', 'produk_id')) {
                $table->string('produk_id')->nullable();
            }

            if (!Schema::hasColumn('komentar', 'berita_id')) {
                $table->string('berita_id')->nullable();
            }

            if (!Schema::hasColumn('komentar', 'karya_id')) {
                $table->string('karya_id')->nullable();
            }

            // Drop polymorphic column
            $drop = [];
            foreach (['komentar_type', 'item_id'] as $col) {
                if (Schema::hasColumn('komentar', $col)) {
                    $drop[] = $col;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
