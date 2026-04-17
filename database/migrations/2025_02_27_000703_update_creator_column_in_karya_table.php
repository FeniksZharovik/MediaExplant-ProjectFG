<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->string('creator', 255)->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->string('creator', 255)->nullable()->change();
        });
    }
};
