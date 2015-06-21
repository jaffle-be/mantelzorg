<?php

use Illuminate\Database\Migrations\Migration;

class CreateAnswerChoises extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_answer_choises', function ($t) {
            $t->integer('answer_id')->unsigned();
            $t->foreign('answer_id')->references('id')->on('questionnaire_answers');
            $t->integer('choise_id')->unsigned();
            $t->foreign('choise_id')->references('id')->on('questionnaire_choises');
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
        Schema::drop('questionnaire_answer_choises');
    }

}