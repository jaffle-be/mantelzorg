<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->boolean('male')->default(1);
            $table->string('phone', 30)->nullable();
            $table->boolean('active')->default(0);
            $table->boolean('admin')->default(0);
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
        Schema::drop('users');
    }

}