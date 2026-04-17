<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisibilitasToKaryaTable extends Migration
{
    public function up(): void
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->enum('visibilitas', ['public', 'private'])->default('public')->after('konten');
        });
    }

    public function down(): void
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->dropColumn('visibilitas');
        });
    }
}
