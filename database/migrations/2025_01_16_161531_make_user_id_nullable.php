<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUserIdNullable extends Migration
{
    /**
     * Jalankan migrasi untuk mengubah kolom user_id menjadi nullable di tabel yang relevan.
     *
     * @return void
     */
    public function up()
    {
        // Mengubah kolom user_id di tabel artikel
        Schema::table('artikel', function (Blueprint $table) {
            $table->string('user_id', 28)->nullable()->change();
        });

        // Mengubah kolom user_id di tabel komentar
        Schema::table('komentar', function (Blueprint $table) {
            $table->string('user_id', 28)->nullable()->change();
        });

        // Mengubah kolom user_id di tabel device_tokens
        Schema::table('device_tokens', function (Blueprint $table) {
            $table->string('user_id', 28)->nullable()->change();
        });

        // Mengubah kolom user_id di tabel reaksi
        Schema::table('reaksi', function (Blueprint $table) {
            $table->string('user_id', 28)->nullable()->change();
        });

        // Mengubah kolom user_id di tabel bookmark
        Schema::table('bookmark', function (Blueprint $table) {
            $table->string('user_id', 28)->nullable()->change();
        });
    }

    /**
     * Balikkan perubahan yang dilakukan oleh fungsi up.
     *
     * @return void
     */
    public function down()
    {
        // Menetapkan kembali kolom user_id agar tidak nullable
        Schema::table('artikel', function (Blueprint $table) {
            $table->string('user_id', 28)->nullable(false)->change();
        });

        Schema::table('komentar', function (Blueprint $table) {
            $table->string('user_id', 28)->nullable(false)->change();
        });

        Schema::table('device_tokens', function (Blueprint $table) {
            $table->string('user_id', 28)->nullable(false)->change();
        });

        Schema::table('reaksi', function (Blueprint $table) {
            $table->string('user_id', 28)->nullable(false)->change();
        });

        Schema::table('bookmark', function (Blueprint $table) {
            $table->string('user_id', 28)->nullable(false)->change();
        });
    }
}
