<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTentangKamiTable extends Migration
{
    public function up()
    {
        Schema::create('tentang_kami', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            $table->string('email', 30)->nullable();
            $table->string('nomorHp', 30)->nullable();
            $table->string('tentangKami', 225)->nullable();
            $table->string('facebook', 150)->nullable();
            $table->string('instagram', 150)->nullable();
            $table->string('linkedin', 150)->nullable();
            $table->string('youtube', 150)->nullable();
            $table->string('kodeEtik', 225)->nullable();
            $table->string('explantContributor', 225)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tentang_kami');
    }
}
