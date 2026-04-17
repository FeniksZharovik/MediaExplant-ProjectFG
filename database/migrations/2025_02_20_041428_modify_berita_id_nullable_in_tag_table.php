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
        Schema::table('tag', function (Blueprint $table) {
            $table->string('berita_id', 12)->nullable()->change(); // Mengubah berita_id menjadi nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tag', function (Blueprint $table) {
            $table->string('berita_id', 12)->nullable(false)->change(); // Mengembalikan ke wajib isi (jika perlu rollback)
        });
    }
};
