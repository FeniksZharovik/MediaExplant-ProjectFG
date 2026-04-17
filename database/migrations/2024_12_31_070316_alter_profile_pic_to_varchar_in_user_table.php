<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            // Ubah kembali tipe kolom profile_pic menjadi VARCHAR(255)
            $table->string('profile_pic', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            // Jika ingin rollback, ubah kembali ke MEDIUMBLOB
            $table->binary('profile_pic')->nullable()->change();
        });
    }
};
