<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsOnPesanTable extends Migration
{
    public function up(): void
    {
        Schema::table('pesan', function (Blueprint $table) {
            // ubah jadi nullable
            $table->string('user_id', 28)->nullable()->change();
            $table->text('detail_pesan')->nullable()->change();
            $table->string('pesan_type', 255)->nullable()->change();
            $table->string('item_id', 255)->nullable()->change();
            $table->string('nama', 100)->nullable()->change();
            $table->string('email', 100)->nullable()->change();

            // enum update (note: harus drop dulu kalau sudah ada tipe enum sebelumnya)
            $table->enum('status_read', ['sudah', 'belum'])->change();
            $table->enum('status', ['laporan', 'masukan'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('pesan', function (Blueprint $table) {
            // kembalikan jadi not null
            $table->string('user_id', 28)->nullable(false)->change();
            $table->text('detail_pesan')->nullable(false)->change();
            $table->string('pesan_type', 255)->nullable(false)->change();
            $table->string('item_id', 255)->nullable(false)->change();
            $table->string('nama', 100)->nullable(false)->change();
            $table->string('email', 100)->nullable(false)->change();

            // rollback enum ke tipe string atau kondisi awal sesuai kebutuhanmu
            $table->string('status_read', 10)->change();
            $table->string('status', 10)->change();
        });
    }
}
