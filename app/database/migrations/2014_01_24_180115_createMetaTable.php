<?php

use Illuminate\Database\Migrations\Migration;

class CreateMetaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('meta_contexts', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('context')->unique();
            $table->timestamps();
        });
        /**
         * This table will be used to persist data which will not be all to common.
         * What i mean is. This table will be used to store data which would not benifit from it's separate table.
         */
        Schema::create('meta_values', function($table)
        {
            $table->increments('id');
            $table->integer('context_id')->unsigned();
            $table->foreign('context_id')->references('id')->on('meta_contexts');
            $table->string('value');
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('meta_values');
        Schema::drop('meta_contexts');
	}

}