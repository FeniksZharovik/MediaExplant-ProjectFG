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
        Schema::create('pesan', function (Blueprint $table) {
            $table->string('id', 12)->primary(); // Primary key as VARCHAR(12)
            $table->string('user_id', 28); // Foreign key referencing user.uid
            $table->string('berita_id', 12); // Foreign key referencing berita.id
            $table->string('komen_id', 12); // Foreign key referencing komentar.id
            $table->text('pesan'); // Message content (TEXT)
            $table->dateTime('created_at'); // Date and time of creation
            $table->string('status_read', 10);// Status read (boolean)
            $table->string('status', 10); // Status (VARCHAR(10))
            $table->text('detail_pesan'); // Detailed message content (TEXT)
            
            // Foreign key constraints
            $table->foreign('user_id')->references('uid')->on('user')->onDelete('cascade');
            $table->foreign('berita_id')->references('id')->on('berita')->onDelete('cascade');
            $table->foreign('komen_id')->references('id')->on('komentar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan');
    }
};