<?php

use Illuminate\Database\Migrations\Migration;

class CreateQuestionairePanels extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('questionnaire_panels', function($t)
        {
            $t->increments('id');
            $t->integer('questionnaire_id')->unsigned();
            $t->foreign('questionnaire_id')->references('id')->on('questionnaires');
            $t->integer('panel_weight');
            $t->string('color')->default('gray');
            $t->string('title', 100);
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
		Schema::drop('questionnaire_panels');
	}

}