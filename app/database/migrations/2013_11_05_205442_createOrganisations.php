<?php

use Illuminate\Database\Migrations\Migration;

class CreateOrganisations extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisations', function ($t) {
            $t->increments('id');
            $t->string('name');
            $t->timestamps();
        });
        Schema::create('locations', function ($t) {
            $t->increments('id');
            $t->integer('organisation_id')->unsigned();
            $t->foreign('organisation_id')->references('id')->on('organisations');
            $t->string('name');
            $t->string('street', 150);
            $t->string('city', 100);
            $t->string('postal', 10);
            $t->string('country', 5)->default('BE');
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
        Schema::drop('locations');
        Schema::drop('organisations');

    }

}