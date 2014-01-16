<?php

use Illuminate\Database\Migrations\Migration;

class CreateChoises extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questionnaire_choises', function($t)
        {
            $t->increments('id');
            $t->integer('question_id')->unsigned();
            $t->foreign('question_id')->references('id')->on('questionnaire_questions');
            $t->string('title');
            $t->integer('sort_weight');
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
		Schema::drop('questionnaire_choises');
	}

}