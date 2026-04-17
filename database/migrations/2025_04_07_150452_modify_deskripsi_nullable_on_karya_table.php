<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDeskripsiNullableOnKaryaTable extends Migration
{
    public function up()
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->text('deskripsi')->nullable(false)->change();
        });
    }
}
