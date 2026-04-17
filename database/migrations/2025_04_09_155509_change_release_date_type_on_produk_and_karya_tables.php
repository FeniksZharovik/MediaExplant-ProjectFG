<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeReleaseDateTypeOnProdukAndKaryaTables extends Migration
{
    public function up()
    {
        // Ubah kolom release_date pada tabel produk
        Schema::table('produk', function (Blueprint $table) {
            $table->dateTime('release_date')->change();
        });

        // Ubah kolom release_date pada tabel karya
        Schema::table('karya', function (Blueprint $table) {
            $table->dateTime('release_date')->change();
        });
    }

    public function down()
    {
        // Kembalikan ke tipe date jika rollback
        Schema::table('produk', function (Blueprint $table) {
            $table->date('release_date')->change();
        });

        Schema::table('karya', function (Blueprint $table) {
            $table->date('release_date')->change();
        });
    }
}
