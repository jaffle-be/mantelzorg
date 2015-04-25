<?php namespace Questionnaire\Jobs;

use Illuminate\Log\Writer;
use Questionnaire\Export\Exporter;
use Questionnaire\Questionnaire;

class ExportJob {

    /**
     * @var Exporter
     */
    protected $export;

    protected $questionnaire;

    protected $log;

    public function __construct(Questionnaire $questionnaire, Exporter $export, Writer $log)
    {
        $this->questionnaire = $questionnaire;
        $this->export = $export;
        $this->log = $log;
    }

    public function fire($job, $payload)
    {
        $id = $payload['id'];

        $this->log->info('job started for ' . $id);

        $start = microtime(true);

        $survey = $this->questionnaire->find($id);

        $survey->load([
            'panels',
            //make sure questions follow the order of the questionnaire to number them in the report. not so transparent
            //but that is how they wanted it.
            'panels.questions' => function($query){
                $query->orderBy('sort');
            },
            //same reasoning applies for the options available to a question.
            'panels.questions.choises' => function($query){
                $query->orderBy('sort_weight');
            }
        ])->all();

        $this->export->generate($survey);

        $time = microtime(true) - $start;

        $this->log->info(sprintf('generating export for %s took %d seconds', $survey->title, $time));
    }

}