<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tag', function (Blueprint $table) {
            $table->dropForeign(['karya_id']);
            $table->dropForeign(['produk_id']);
            $table->dropColumn(['karya_id', 'produk_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tag', function (Blueprint $table) {
            $table->string('produk_id', 12)->nullable();
            $table->string('karya_id', 12)->nullable();
            $table->foreign('produk_id')->references('id')->on('produk')->onDelete('cascade');
            $table->foreign('karya_id')->references('id')->on('karya')->onDelete('cascade');
        });
    }
};
