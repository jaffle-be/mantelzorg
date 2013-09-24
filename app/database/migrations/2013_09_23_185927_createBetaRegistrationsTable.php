<?php

use Illuminate\Database\Migrations\Migration;

class CreateBetaRegistrationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('beta_registrations', function($table)
        {
            $table->increments('id');
            $table->string('email', 100)->unique();
            $table->string('firstname');
            $table->string('sirname');
            $table->string('organisation', 100);
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
		Schema::drop('beta_registrations');
	}

}