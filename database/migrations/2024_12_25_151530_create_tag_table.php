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
        Schema::create('tag', function (Blueprint $table) {
            $table->string('tag', 12)->primary(); // Primary key as VARCHAR(12)
            $table->string('nama_tag', 20); // Tag name (VARCHAR(20))
            $table->string('berita_id', 12); // Foreign key referencing berita.id
            
            // Foreign key constraint
            $table->foreign('berita_id')->references('id')->on('berita')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag');
    }
};