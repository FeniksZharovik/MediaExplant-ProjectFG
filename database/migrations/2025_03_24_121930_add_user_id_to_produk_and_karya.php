<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Menambahkan user_id ke tabel produk
        Schema::table('produk', function (Blueprint $table) {
            $table->string('user_id', 28)->after('id'); // Tambahkan kolom user_id
            $table->foreign('user_id')->references('uid')->on('user')->onDelete('cascade');
        });

        // Menambahkan user_id ke tabel karya
        Schema::table('karya', function (Blueprint $table) {
            $table->string('user_id', 28)->after('id'); // Tambahkan kolom user_id
            $table->foreign('user_id')->references('uid')->on('user')->onDelete('cascade');
        });
    }

    public function down()
    {
        // Menghapus foreign key dan kolom user_id dari tabel produk
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        // Menghapus foreign key dan kolom user_id dari tabel karya
        Schema::table('karya', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
