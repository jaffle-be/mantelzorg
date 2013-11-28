<?php

use Illuminate\Database\Migrations\Migration;

class CreateOudere extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ouderen', function($t){
            $t->increments('id');
            $t->string('email', 150)->unique()->nullable();
            $t->string('firstname', 100);
            $t->string('lastname', 100);
            $t->boolean('male');
            $t->string('street', 100);
            $t->string('postal', 10);
            $t->string('city', 100);
            $t->string('phone', 25);
            $t->date('birthday');
            $t->integer('mantelzorger_id')->unsigned();
            $t->foreign('mantelzorger_id')->references('id')->on('mantelzorgers');
            $t->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ouderen');
	}

}