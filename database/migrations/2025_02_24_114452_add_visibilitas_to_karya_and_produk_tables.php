<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        DB::statement("ALTER TABLE `user` MODIFY `profile_pic` MEDIUMBLOB");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `user` MODIFY `profile_pic` VARCHAR(255)");
    }
};
