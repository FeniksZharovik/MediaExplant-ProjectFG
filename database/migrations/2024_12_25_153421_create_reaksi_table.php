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
        Schema::create('reaksi', function (Blueprint $table) {
            $table->string('id', 12)->primary(); // Primary key as VARCHAR(12)
            $table->string('user_id', 28); // Foreign key referencing user.uid
            $table->string('berita_id', 12); // Foreign key referencing berita.id
            $table->string('jenis_reaksi', 10); // Reaction type (VARCHAR(10))
            $table->dateTime('tanggal_reaksi'); // Date and time of the reaction
            
            // Foreign key constraints
            $table->foreign('user_id')->references('uid')->on('user')->onDelete('cascade');
            $table->foreign('berita_id')->references('id')->on('berita')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reaksi');
    }
};