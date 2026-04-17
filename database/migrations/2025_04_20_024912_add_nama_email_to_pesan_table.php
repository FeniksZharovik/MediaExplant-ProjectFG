<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaEmailToPesanTable extends Migration
{
    public function up(): void
    {
        Schema::table('pesan', function (Blueprint $table) {
            $table->string('nama', 100)->after('item_id');
            $table->string('email', 100)->after('nama');
        });
    }

    public function down(): void
    {
        Schema::table('pesan', function (Blueprint $table) {
            $table->dropColumn(['nama', 'email']);
        });
    }
}

