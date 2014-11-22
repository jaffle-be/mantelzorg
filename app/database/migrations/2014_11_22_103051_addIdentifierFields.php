<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdentifierFields extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ouderen', function (Blueprint $table)
        {
			$table->string('identifier', 20)->after('id');
        });

        Schema::table('mantelzorgers', function (Blueprint $table)
        {
            $table->string('identifier', 20)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ouderen', function(Blueprint $table){
            $table->dropColumn('identifier');
        });
        Schema::table('mantelzorgers', function(Blueprint $table){
            $table->dropColumn('identifier');
        });
    }

}
