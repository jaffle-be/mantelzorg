<?php

use Illuminate\Database\Migrations\Migration;

class CreateQuestions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questionnaire_questions', function($t)
        {
            $t->increments('id');
            $t->integer('questionnaire_id')->unsigned();
            $t->foreign('questionnaire_id')->references('id')->on('questionnaires');
            $t->integer('questionnaire_panel_id')->unsigned();
            $t->foreign('questionnaire_panel_id')->references('id')->on('questionnaire_panels');
            $t->string('title', 100);
            $t->string('question', 500);
            $t->boolean('multiple_choise')->default(0);
            $t->boolean('summary_question')->default(0);
            $t->boolean('explainable')->default(0);
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
		Schema::drop('questionnaire_questions');
	}

}