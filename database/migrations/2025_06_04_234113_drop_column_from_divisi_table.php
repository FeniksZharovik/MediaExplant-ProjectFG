<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnFromDivisiTable extends Migration
{
    public function up()
    {
        Schema::table('organisasi_divisi', function (Blueprint $table) {
            $table->dropColumn('column');
        });
    }

    public function down()
    {
        Schema::table('organisasi_divisi', function (Blueprint $table) {
            $table->integer('column')->nullable();
        });
    }
}
