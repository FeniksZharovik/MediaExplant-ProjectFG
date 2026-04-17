<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reaksi', function (Blueprint $table) {
            // Drop foreign keys dulu (jika ada)
            if (Schema::hasColumn('reaksi', 'berita_id')) {
                $table->dropForeign(['berita_id']);
            }

            if (Schema::hasColumn('reaksi', 'produk_id')) {
                $table->dropForeign(['produk_id']);
            }

            if (Schema::hasColumn('reaksi', 'karya_id')) {
                $table->dropForeign(['karya_id']);
            }

            // Drop kolom lama jika masih ada
            $columnsToDrop = [];
            foreach (['berita_id', 'produk_id', 'karya_id'] as $col) {
                if (Schema::hasColumn('reaksi', $col)) {
                    $columnsToDrop[] = $col;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            // Tambah kolom polymorphic jika belum ada
            if (!Schema::hasColumn('reaksi', 'reaksi_type')) {
                $table->string('reaksi_type');
            }

            if (!Schema::hasColumn('reaksi', 'item_id')) {
                $table->string('item_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reaksi', function (Blueprint $table) {
            // Tambahkan kembali kolom lama
            if (!Schema::hasColumn('reaksi', 'produk_id')) {
                $table->string('produk_id')->nullable();
            }

            if (!Schema::hasColumn('reaksi', 'berita_id')) {
                $table->string('berita_id')->nullable();
            }

            if (!Schema::hasColumn('reaksi', 'karya_id')) {
                $table->string('karya_id')->nullable();
            }

            // Drop kolom polymorphic jika ada
            $colsToDrop = [];
            foreach (['reaksi_type', 'item_id'] as $col) {
                if (Schema::hasColumn('reaksi', $col)) {
                    $colsToDrop[] = $col;
                }
            }

            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};
