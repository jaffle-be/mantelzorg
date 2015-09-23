<?php

use App\Questionnaire\Export\Report;
use App\Questionnaire\Questionnaire;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuestionnaireReportsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_reports', function (Blueprint $table) {

            $table->increments('id');
            $table->string('filename');
            $table->integer('survey_count');

            $table->integer('questionnaire_id', false, true);
            $table->foreign('questionnaire_id', 'report_to_survey')->references('id')->on('questionnaires')->onDelete('cascade');

            $table->integer('organisation_id', false, true)->nullable();
            $table->foreign('organisation_id', 'report_to_organisation')->references('id')->on('organisations')->onDelete('cascade');

            $table->integer('user_id', false, true)->nullable();
            $table->foreign('user_id', 'report_to_user')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        $this->createInitialReports();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('questionnaire_reports', function (Blueprint $table) {
            $table->dropForeign('report_to_survey');
            $table->dropForeign('report_to_organisation');
            $table->dropForeign('report_to_user');
        });
    }

    /**
     * this is just a function to create all the necessary records for the files that already exist.
     */
    protected function createInitialReports()
    {
        $survey = Questionnaire::active()->first();

        foreach($this->files() as $file)
        {
            $date = $this->parseDate($file);

            $report = new Report([
                'filename' => $file,
                'survey_count' => -1,
                'questionnaire_id' => $survey->id,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            $report->save(['timestamps' => false]);
        }
    }

    /**
     * Return reports that already existed before implementing this table.
     * @return array
     */
    protected function files()
    {
        $files = scandir(storage_path('exports'));

        return array_filter($files, function($item)
        {
            return !in_array(strtolower($item), ['.', '..', '.ds_store', '.gitignore']);
        });
    }

    protected function parseDate($file)
    {
        $matches = [];

        if(preg_match('/(\d{2}-){2}\d{2} (\d{2}:){2}\d{2}/', $file, $matches) !== 1)
        {
            throw new \Exception('No date found in filename : ' . $file);
        }

        //prefix with 20 to get 2015 instead of 15
        return '20' . $matches[0];
    }
}
