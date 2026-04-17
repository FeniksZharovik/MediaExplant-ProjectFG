<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrganisasiDivisiTable extends Migration
{
    public function up()
    {
        Schema::table('organisasi_divisi', function (Blueprint $table) {
            // Ubah kolom id jadi varchar(50)
            $table->string('id', 50)->change();

            // Kolom row tidak boleh null
            $table->integer('row')->nullable(false)->change();

            // Tambah created_at (nullable)
            $table->dateTime('created_at')->nullable();

            // Tambah updated_at (not nullable)
            $table->dateTime('updated_at');
        });
    }

    public function down()
    {
        Schema::table('organisasi_divisi', function (Blueprint $table) {
            // Balik ke varchar(12) jika sebelumnya begitu
            $table->string('id', 12)->change();

            // row jadi nullable kembali
            $table->integer('row')->nullable()->change();

            // Hapus kolom waktu
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
}
