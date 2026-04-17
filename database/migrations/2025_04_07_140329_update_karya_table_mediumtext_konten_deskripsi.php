<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKaryaTableMediumtextKontenDeskripsi extends Migration
{
    public function up(): void
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->mediumText('deskripsi')->change();
            $table->mediumText('konten')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->text('deskripsi')->change();
            $table->text('konten')->nullable()->change();
        });
    }
}
