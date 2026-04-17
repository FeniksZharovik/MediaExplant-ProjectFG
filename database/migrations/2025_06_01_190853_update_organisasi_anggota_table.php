<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrganisasiAnggotaTable extends Migration
{
    public function up()
    {
        Schema::table('organisasi_anggota', function (Blueprint $table) {
            // Ubah panjang id menjadi 36
            $table->string('id', 36)->change();

            // Jadikan title_perangkat nullable
            $table->string('title_perangkat', 30)->nullable()->change();

            // Ubah id_divisi jadi varchar(50)
            $table->string('id_divisi', 50)->change();

            // Tambahkan kolom created_at dan updated_at (nullable)
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('organisasi_anggota', function (Blueprint $table) {
            // Kembalikan perubahan ke kondisi awal
            $table->string('id', 12)->change();
            $table->string('title_perangkat', 30)->nullable(false)->change();
            $table->string('id_divisi', 12)->change();

            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
}
