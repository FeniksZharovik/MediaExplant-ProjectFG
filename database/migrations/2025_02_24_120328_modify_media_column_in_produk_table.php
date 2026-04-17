<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE produk MODIFY COLUMN media LONGBLOB');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE produk MODIFY COLUMN media VARCHAR(255)');
    }
};
