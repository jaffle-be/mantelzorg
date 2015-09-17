<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCascadesForExistingRelations extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign('locations_organisation_id_foreign');
            $table->foreign('organisation_id', 'location_to_organisation')->references('id')->on('organisations')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_organisation_id_foreign');
            $table->dropForeign('users_organisation_location_id_foreign');
            $table->foreign('organisation_id', 'user_to_organisation')->references('id')->on('organisations')->onDelete('cascade');
            $table->foreign('organisation_location_id', 'user_to_location')->references('id')->on('locations')->onDelete('cascade');
        });

        Schema::table('questionnaire_panels', function (Blueprint $table) {
            $table->dropForeign('questionnaire_panels_questionnaire_id_foreign');
            $table->foreign('questionnaire_id', 'panel_to_survey')->references('id')->on('questionnaires')->onDelete('cascade');
        });

        Schema::table('questionnaire_questions', function (Blueprint $table) {
            $table->dropForeign('questionnaire_questions_questionnaire_id_foreign');
            $table->foreign('questionnaire_id', 'question_to_survey')->references('id')->on('questionnaires')->onDelete('cascade');
            $table->dropForeign('questionnaire_questions_questionnaire_panel_id_foreign');
            $table->foreign('questionnaire_panel_id', 'question_to_panel')->references('id')->on('questionnaire_panels')->onDelete('cascade');
        });

        Schema::table('questionnaire_choises', function (Blueprint $table) {
            $table->dropForeign('questionnaire_choises_question_id_foreign');
            $table->foreign('question_id', 'choise_to_question')->references('id')->on('questionnaire_questions')->onDelete('cascade');
        });

        Schema::table('ouderen', function (Blueprint $table){
            $table->dropForeign('ouderen_mantelzorger_id_foreign');
            $table->foreign('mantelzorger_id', 'oudere_to_mantelzorger')->references('id')->on('mantelzorgers')->onDelete('cascade');
        });

        Schema::table('questionnaire_survey_sessions', function (Blueprint $table)
        {
            $table->dropForeign('questionnaire_survey_sessions_mantelzorger_id_foreign');
            $table->foreign('mantelzorger_id', 'survey_session_to_mantelzorger')->references('id')->on('mantelzorgers')->onDelete('cascade');

            $table->dropForeign('questionnaire_survey_sessions_oudere_id_foreign');
            $table->foreign('oudere_id', 'survey_session_to_oudere')->references('id')->on('ouderen')->onDelete('cascade');

            $table->dropForeign('questionnaire_survey_sessions_user_id_foreign');
            $table->foreign('user_id', 'survey_session_to_user')->references('id')->on('users')->onDelete('cascade');

            $table->dropForeign('questionnaire_survey_sessions_questionnaire_id_foreign');
            $table->foreign('questionnaire_id', 'survey_session_to_survey')->references('id')->on('questionnaires')->onDelete('cascade');
        });

        Schema::table('questionnaire_answers', function (Blueprint $table){
            $table->dropForeign('questionnaire_answers_session_id_foreign');
            $table->foreign('session_id', 'answer_to_session')->references('id')->on('questionnaire_survey_sessions')->onDelete('cascade');

            $table->dropForeign('questionnaire_answers_question_id_foreign');
            $table->foreign('question_id', 'answer_to_question')->references('id')->on('questionnaire_questions')->onDelete('cascade');
        });

        Schema::table('questionnaire_answer_choises', function (Blueprint $table){
            $table->dropForeign('questionnaire_answer_choises_answer_id_foreign');
            $table->foreign('answer_id', 'answered_choise_to_answer')->references('id')->on('questionnaire_answers')->onDelete('cascade');

            $table->dropForeign('questionnaire_answer_choises_choise_id_foreign');
            $table->foreign('choise_id', 'answered_choise_to_choise')->references('id')->on('questionnaire_choises')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign('location_to_organisation');
            $table->foreign('organisation_id', 'locations_organisation_id_foreign')->references('id')->on('organisations');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('user_to_organisation');
            $table->dropForeign('user_to_location');
            $table->foreign('organisation_id', 'users_organisation_id_foreign')->references('id')->on('organisations');
            $table->foreign('organisation_location_id', 'users_organisation_location_id_foreign')->references('id')->on('locations');
        });

        Schema::table('questionnaire_panels', function (Blueprint $table) {
            $table->dropForeign('panel_to_survey');
            $table->foreign('questionnaire_id', 'questionnaire_panels_questionnaire_id_foreign')->references('id')->on('questionnaires');
        });

        Schema::table('questionnaire_questions', function (Blueprint $table) {
            $table->dropForeign('question_to_survey');
            $table->foreign('questionnaire_id', 'questionnaire_questions_questionnaire_id_foreign')->references('id')->on('questionnaires');
            $table->dropForeign('question_to_panel');
            $table->foreign('questionnaire_panel_id', 'questionnaire_questions_questionnaire_panel_id_foreign')->references('id')->on('questionnaire_panels');
        });

        Schema::table('questionnaire_choises', function (Blueprint $table) {
            $table->dropForeign('choise_to_question');
            $table->foreign('question_id', 'questionnaire_choises_question_id_foreign')->references('id')->on('questionnaire_questions');
        });

        Schema::table('ouderen', function (Blueprint $table) {
            $table->dropForeign('oudere_to_mantelzorger');
            $table->foreign('mantelzorger_id', 'ouderen_mantelzorger_id_foreign')->references('id')->on('mantelzorgers');
        });

        Schema::table('questionnaire_survey_sessions', function (Blueprint $table) {
            $table->dropForeign('survey_session_to_mantelzorger');
            $table->foreign('mantelzorger_id', 'questionnaire_survey_sessions_mantelzorger_id_foreign')->references('id')->on('mantelzorgers');

            $table->dropForeign('survey_session_to_oudere');
            $table->foreign('oudere_id', 'questionnaire_survey_sessions_oudere_id_foreign')->references('id')->on('ouderen');

            $table->dropForeign('survey_session_to_user');
            $table->foreign('user_id', 'questionnaire_survey_sessions_user_id_foreign')->references('id')->on('users');

            $table->dropForeign('survey_session_to_survey');
            $table->foreign('questionnaire_id', 'questionnaire_survey_sessions_questionnaire_id_foreign')->references('id')->on('questionnaires');
        });

        Schema::table('questionnaire_answers', function (Blueprint $table) {
            $table->dropForeign('answer_to_session');
            $table->foreign('session_id', 'questionnaire_answers_session_id_foreign')->references('id')->on('questionnaire_survey_sessions');
            $table->dropForeign('answer_to_question');
            $table->foreign('question_id', 'questionnaire_answers_question_id_foreign')->references('id')->on('questionnaire_questions');
        });

        Schema::table('questionnaire_answer_choises', function (Blueprint $table) {
            $table->dropForeign('answered_choise_to_answer');
            $table->foreign('answer_id', 'questionnaire_answer_choises_answer_id_foreign')->references('id')->on('questionnaire_answers');
            $table->dropForeign('answered_choise_to_choise');
            $table->foreign('choise_id', 'questionnaire_answer_choises_choise_id_foreign')->references('id')->on('questionnaire_choises');
        });
    }

}
