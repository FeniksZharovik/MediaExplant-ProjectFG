<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->mediumText('media')->change();
        });
    }

    public function down()
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->string('media', 255)->change(); // Sesuaikan dengan tipe data sebelumnya
        });
    }
};
