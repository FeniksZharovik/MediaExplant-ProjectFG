<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStarToPesanTable extends Migration
{
    public function up()
    {
        Schema::table('pesan', function (Blueprint $table) {
            $table->enum('star', ['iya', 'tidak'])->after('media');
        });
    }

    public function down()
    {
        Schema::table('pesan', function (Blueprint $table) {
            $table->dropColumn('star');
        });
    }
}
