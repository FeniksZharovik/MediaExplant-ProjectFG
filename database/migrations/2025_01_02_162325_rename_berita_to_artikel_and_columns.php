<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename tabel berita ke artikel
        Schema::rename('berita', 'artikel');

        // Rename foreign key columns pada tabel terkait
        Schema::table('tag', function (Blueprint $table) {
            $table->renameColumn('berita_id', 'artikel_id');
        });

        Schema::table('pesan', function (Blueprint $table) {
            $table->renameColumn('berita_id', 'artikel_id');
        });

        Schema::table('reaksi', function (Blueprint $table) {
            $table->renameColumn('berita_id', 'artikel_id');
        });

        Schema::table('komentar', function (Blueprint $table) {
            $table->renameColumn('berita_id', 'artikel_id');
        });

        Schema::table('bookmark', function (Blueprint $table) {
            $table->renameColumn('berita_id', 'artikel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename tabel artikel kembali ke berita
        Schema::rename('artikel', 'berita');

        // Rename foreign key columns kembali ke berita_id
        Schema::table('tag', function (Blueprint $table) {
            $table->renameColumn('artikel_id', 'berita_id');
        });

        Schema::table('pesan', function (Blueprint $table) {
            $table->renameColumn('artikel_id', 'berita_id');
        });

        Schema::table('reaksi', function (Blueprint $table) {
            $table->renameColumn('artikel_id', 'berita_id');
        });

        Schema::table('komentar', function (Blueprint $table) {
            $table->renameColumn('artikel_id', 'berita_id');
        });

        Schema::table('bookmark', function (Blueprint $table) {
            $table->renameColumn('artikel_id', 'berita_id');
        });
    }
};
