<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah sementara id ke BIGINT tanpa AUTO_INCREMENT (agar bisa DROP PRIMARY KEY)
        DB::statement('ALTER TABLE personal_access_tokens MODIFY id BIGINT UNSIGNED NOT NULL');

        // 2. DROP PRIMARY KEY
        DB::statement('ALTER TABLE personal_access_tokens DROP PRIMARY KEY');

        // 3. Ubah id jadi AUTO_INCREMENT dan jadikan PRIMARY KEY
        DB::statement('ALTER TABLE personal_access_tokens MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');

        // 4. Ubah tokenable_id jadi CHAR(36)
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->char('tokenable_id', 36)->change();
        });
    }

    public function down(): void
    {
        // 1. Drop primary key (kalau masih ada)
        DB::statement('ALTER TABLE personal_access_tokens DROP PRIMARY KEY');

        // 2. Ubah id ke VARCHAR(36)
        DB::statement('ALTER TABLE personal_access_tokens MODIFY id VARCHAR(36) NOT NULL');

        // 3. Tambahkan primary key ke id
        DB::statement('ALTER TABLE personal_access_tokens ADD PRIMARY KEY(id)');

        // 4. Balikin tokenable_id ke unsignedBigInteger
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('tokenable_id')->change();
        });
    }
};
