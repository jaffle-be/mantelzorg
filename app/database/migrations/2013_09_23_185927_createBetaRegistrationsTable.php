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
            $table->integer('id')->increments();
            $table->string('email', 100);
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