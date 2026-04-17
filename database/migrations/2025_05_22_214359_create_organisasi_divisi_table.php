<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisasiDivisiTable extends Migration
{
    public function up()
    {
        Schema::create('organisasi_divisi', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            $table->unique('id');
            $table->string('nama_divisi', 50)->unique();
            $table->integer('column')->nullable();
            $table->integer('row')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('organisasi_divisi');
    }
}
