<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortFieldToQuestions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questionnaire_questions', function (Blueprint $table) {
            $table->integer('sort')->nullable();
        });

        //auto add start sorting.
        $current = null;
        $teller = 0;
        \App\Questionnaire\Question::orderBy('questionnaire_panel_id')->orderBy('id')->chunk(250, function($questions) use (&$current, &$teller){

            foreach($questions as $question){
                if($current != $question->questionnaire_panel_id)
                {
                    $current = $question->questionnaire_panel_id;
                    $teller = 1;
                }
                $question->sort = $teller;
                $question->save();
                $teller++;
            }
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaire_questions', function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
}
