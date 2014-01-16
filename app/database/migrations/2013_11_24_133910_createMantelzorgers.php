<?php

use Illuminate\Database\Migrations\Migration;

class CreateMantelzorgers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mantelzorgers', function($t){
            $t->increments('id');
            $t->string('email', 150)->unique();
            $t->string('firstname', 100);
            $t->string('lastname', 100);
            $t->boolean('male');
            $t->string('street', 200);
            $t->string('postal', 5);
            $t->string('city', 100);
            $t->string('phone', 20);
            $t->date('birthday');
            $t->integer('hulpverlener_id')->unsigned();
            $t->foreign('hulpverlener_id')->references('id')->on('users');
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
		Schema::drop('mantelzorgers');
	}

}