<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('kategori');
            $table->string('media');
            $table->date('release_date');
        });

        Schema::create('karya', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('kategori');
            $table->string('media');
            $table->date('release_date');
        });

        // Menambahkan kolom referensi ke tabel yang sudah ada
        $tables = ['komentar', 'tag', 'pesan', 'reaksi', 'bookmark'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('produk_id', 12)->nullable();
                $table->string('karya_id', 12)->nullable();
                $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
                $table->foreign('karya_id')->references('id')->on('karya')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        $tables = ['komentar', 'tag', 'pesan', 'reaksi', 'bookmark'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['produk_id']);
                $table->dropForeign(['karya_id']);
                $table->dropColumn(['produk_id', 'karya_id']);
            });
        }

        Schema::dropIfExists('produk');
        Schema::dropIfExists('karya');
    }
};
