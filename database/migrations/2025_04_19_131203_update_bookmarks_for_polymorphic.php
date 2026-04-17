<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookmark', function (Blueprint $table) {
            // Drop foreign keys jika constraint masih ada
            if (Schema::hasColumn('bookmark', 'berita_id')) {
                $table->dropForeign(['berita_id']);
            }

            if (Schema::hasColumn('bookmark', 'produk_id')) {
                $table->dropForeign(['produk_id']);
            }

            if (Schema::hasColumn('bookmark', 'karya_id')) {
                $table->dropForeign(['karya_id']);
            }

            // Drop kolom jika masih ada
            $columnsToDrop = [];
            foreach (['berita_id', 'produk_id', 'karya_id'] as $column) {
                if (Schema::hasColumn('bookmark', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            // Tambahkan kolom baru jika belum ada
            if (!Schema::hasColumn('bookmark', 'bookmark_type')) {
                $table->string('bookmark_type');
            }

            if (!Schema::hasColumn('bookmark', 'item_id')) {
                $table->string('item_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookmark', function (Blueprint $table) {
            // Tambah kembali kolom lama
            if (!Schema::hasColumn('bookmark', 'produk_id')) {
                $table->string('produk_id')->nullable();
            }

            if (!Schema::hasColumn('bookmark', 'berita_id')) {
                $table->string('berita_id')->nullable();
            }

            if (!Schema::hasColumn('bookmark', 'karya_id')) {
                $table->string('karya_id')->nullable();
            }

            // Drop kolom baru jika ada
            $colsToDrop = [];
            foreach (['bookmark_type', 'item_id'] as $col) {
                if (Schema::hasColumn('bookmark', $col)) {
                    $colsToDrop[] = $col;
                }
            }

            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
