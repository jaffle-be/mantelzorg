<?php

use Illuminate\Database\Migrations\Migration;

class AllowNullForRememberMeToken extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `users` CHANGE `remember_token` `remember_token` VARCHAR(100)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT ''");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `users` CHANGE `remember_token` `remember_token` VARCHAR(100)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NOT NULL  DEFAULT ''");
    }

}
