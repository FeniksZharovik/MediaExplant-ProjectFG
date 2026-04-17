<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKaryaTableAddKontenAndNullableCreator extends Migration
{
    public function up(): void
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->text('konten')->nullable()->after('deskripsi'); // sesuaikan posisi jika mau
            $table->string('creator')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('karya', function (Blueprint $table) {
            $table->dropColumn('konten');
            $table->string('creator')->nullable(false)->change(); // kembalikan seperti semula
        });
    }
}
