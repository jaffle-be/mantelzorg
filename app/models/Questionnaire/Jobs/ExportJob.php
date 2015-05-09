<?php namespace Questionnaire\Jobs;

use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Events\Dispatcher;
use Illuminate\Log\Writer;
use Illuminate\Queue\Jobs\Job;
use Questionnaire\Export\Exporter;
use Questionnaire\Export\ExportLogger;
use Questionnaire\Questionnaire;
use User;

class ExportJob {

    /**
     * @var Exporter
     */
    protected $export;

    protected $questionnaire;

    protected $logger;

    protected $events;

    protected $user;

    public function __construct(Questionnaire $questionnaire, Exporter $export, ExportLogger $logger, Dispatcher $events, User $user)
    {
        $this->questionnaire = $questionnaire;
        $this->export = $export;
        $this->logger = $logger;
        $this->events = $events;
        $this->user = $user;
    }

    public function fire(Job $job, $payload)
    {
        try{
            $this->logger->start($payload['id']);

            $survey = $this->questionnaire->find($payload['id']);

            $user = $this->user->find($payload['userid']);

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

            $filename = $this->export->generate($survey);

            $this->logger->stop($survey);

            \Event::fire('rapport.generated', [$user, $survey, $filename]);

            $job->delete();
        }
        catch(Exception $e)
        {
            $this->logger->error($e);

            \Event::fire('rapport.failed', [$user, $survey]);
        }
    }

}