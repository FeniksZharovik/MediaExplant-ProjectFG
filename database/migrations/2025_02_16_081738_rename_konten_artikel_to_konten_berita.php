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
        // Rename column konten_artikel menjadi konten_berita di tabel berita
        Schema::table('berita', function (Blueprint $table) {
            $table->renameColumn('konten_artikel', 'konten_berita');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan perubahan jika rollback
        Schema::table('berita', function (Blueprint $table) {
            $table->renameColumn('konten_berita', 'konten_artikel');
        });
    }
};
