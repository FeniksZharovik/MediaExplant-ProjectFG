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
        Schema::create('komentar', function (Blueprint $table) {
            $table->string('id', 12)->primary(); // Primary key as VARCHAR(12)
            $table->string('user_id', 28); // Foreign key referencing user.uid
            $table->string('berita_id', 12); // Foreign key referencing `berita.id`
            $table->text('isi_komentar'); // Comment content
            $table->dateTime('tanggal_komentar'); // Date and time of the comment
            
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
        Schema::dropIfExists('komentar');
    }
};