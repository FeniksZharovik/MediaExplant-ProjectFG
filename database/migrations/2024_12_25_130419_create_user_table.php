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
        Schema::create('user', function (Blueprint $table) {
            $table->string('uid', 28)->primary(); // Primary key as VARCHAR(28)
            $table->string('nama_pengguna', 60); // Username
            $table->string('password', 100); // Password
            $table->string('email', 100); // Email (no longer unique)
            $table->string('profile_pic')->nullable(); // Profile Picture
            $table->string('role'); // User Role
            $table->text('kredensial')->nullable(); // Credentials
            $table->string('nama_lengkap', 100); // Full Name
            // Removed timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};  