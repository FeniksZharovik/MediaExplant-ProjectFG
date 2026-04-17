<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTentangKamiTable extends Migration
{
    public function up()
    {
        Schema::table('tentang_kami', function (Blueprint $table) {
            // Ubah kolom id menjadi varchar(50)
            $table->string('id', 50)->change();

            // Ubah tentangKami menjadi longtext
            $table->longText('tentangKami')->nullable()->change();

            // Tambah kolom fokus_utama sebagai longtext
            $table->longText('fokus_utama')->nullable();

            // Ubah kodeEtik menjadi longtext
            $table->longText('kodeEtik')->nullable()->change();

            // Ubah explantContributor menjadi longtext
            $table->longText('explantContributor')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('tentang_kami', function (Blueprint $table) {
            // Kembalikan ke bentuk sebelumnya
            $table->string('id', 12)->change();
            $table->string('tentangKami', 225)->nullable()->change();
            $table->dropColumn('fokus_utama');
            $table->string('kodeEtik', 225)->nullable()->change();
            $table->string('explantContributor', 225)->nullable()->change();
        });
    }
}
