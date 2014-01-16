<?php

use Illuminate\Database\Migrations\Migration;

class CreateAnswers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questionnaire_answers', function($t)
        {
            $t->increments('id');
            $t->integer('session_id')->unsigned();
            $t->foreign('session_id')->references('id')->on('questionnaire_survey_sessions');
            $t->integer('question_id')->unsigned();
            $t->foreign('question_id')->references('id')->on('questionnaire_questions');
            $t->string('explanation', 500)->nullable();
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
		Schema::drop('questionnaire_answers');
	}

}