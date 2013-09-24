<?php

use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admins', function($table){
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('firstname');
            $table->string('lastname');
            $table->boolean('active')->default(0);
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
        Schema::drop('admins');
	}

}