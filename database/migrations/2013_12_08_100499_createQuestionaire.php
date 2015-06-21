<?php

use Illuminate\Database\Migrations\Migration;

class CreateQuestionaire extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires', function ($t) {
            $t->increments('id');
            $t->string('title', 100);
            $t->boolean('active');
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
        Schema::drop('questionnaires');
    }

}