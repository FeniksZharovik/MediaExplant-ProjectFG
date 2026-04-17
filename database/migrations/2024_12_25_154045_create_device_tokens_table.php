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
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->string('id', 12)->primary(); // Primary key as VARCHAR(12)
            $table->string('user_id', 28); // Foreign key referencing user.uid
            $table->text('device_token'); // Device token (TEXT)
            
            // Foreign key constraint
            $table->foreign('user_id')->references('uid')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};