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
        Schema::create('berita', function (Blueprint $table) {
            $table->string('id', 12)->primary(); // Primary key as VARCHAR(12)
            $table->text('judul'); // Title (TEXT)
            $table->dateTime('tanggal_diterbitkan'); // Publication date (DATETIME)
            $table->unsignedInteger('view_count')->default(0); // View count (unsigned integer)
            $table->string('user_id', 28); // Foreign key referencing user.uid
            $table->string('kategori'); // Category (string)
            $table->mediumText('konten_artikel'); // Article content (MEDIUMTEXT)
            $table->string('visibilitas'); // Visibility (string)
            
            // Foreign key constraint
            $table->foreign('user_id')->references('uid')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};