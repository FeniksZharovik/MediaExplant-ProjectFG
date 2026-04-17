<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisasiAnggotaTable extends Migration
{
    public function up()
    {
        Schema::create('organisasi_anggota', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            $table->string('title_perangkat', 30);
            $table->string('id_divisi', 12);
            $table->string('uid', 28);

            // Foreign Key Constraints
            $table->foreign('id_divisi')->references('id')->on('organisasi_divisi')->onDelete('cascade');
            $table->foreign('uid')->references('uid')->on('user')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('organisasi_anggota');
    }
}
