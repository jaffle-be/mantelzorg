<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class SetDefaultsForTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `ouderen` CHANGE `details_diagnose` `details_diagnose` TEXT  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL;');
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `ouderen` CHANGE `details_diagnose` `details_diagnose` TEXT  CHARACTER SET utf8  COLLATE utf8_unicode_ci NOT NULL;');
    }

}
