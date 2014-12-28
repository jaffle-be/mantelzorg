<?php

use Illuminate\Database\Migrations\Migration;

class CreateInstrumentSession extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_survey_sessions', function ($t) {
            $t->increments('id');
            $t->integer('user_id')->unsigned();
            $t->foreign('user_id')->references('id')->on('users');
            $t->integer('oudere_id')->unsigned();
            $t->foreign('oudere_id')->references('id')->on('ouderen');
            $t->integer('mantelzorger_id')->unsigned();
            $t->foreign('mantelzorger_id')->references('id')->on('mantelzorgers');
            $t->integer('questionnaire_id')->unsigned();
            $t->foreign('questionnaire_id')->references('id')->on('questionnaires');
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
        Schema::drop('questionnaire_survey_sessions');
    }

}