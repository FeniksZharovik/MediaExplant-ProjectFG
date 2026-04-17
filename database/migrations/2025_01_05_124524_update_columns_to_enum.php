<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsToEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->enum('role', ['Penulis', 'Pembaca', 'Admin'])->change();
        });

        Schema::table('reaksi', function (Blueprint $table) {
            $table->enum('jenis_reaksi', ['Suka', 'Tidak Suka'])->change();
        });

        Schema::table('artikel', function (Blueprint $table) {
            $table->enum('visibilitas', ['public', 'private'])->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('role')->change(); // Kembalikan ke tipe data sebelumnya jika perlu
        });

        Schema::table('reaksi', function (Blueprint $table) {
            $table->string('jenis_reaksi')->change(); // Kembalikan ke tipe data sebelumnya
        });

        Schema::table('artikel', function (Blueprint $table) {
            $table->string('visibilitas')->change(); // Kembalikan ke tipe data sebelumnya
        });
    }
}
