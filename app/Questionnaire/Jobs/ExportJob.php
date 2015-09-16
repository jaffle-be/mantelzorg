<?php namespace App\Questionnaire\Jobs;

use App\Commands\Command;
use App\Questionnaire\Export\Exporter;
use App\Questionnaire\Export\ExportLogger;
use App\Questionnaire\Questionnaire;
use App\User;
use Exception;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;

class ExportJob extends Command implements ShouldQueue, SelfHandling{

    protected $id;

    protected $userid;

    protected $filters;

    public function __construct($id, $userid, $filters)
    {
        $this->id = $id;
        $this->userid = $userid;
        $this->filters = $filters;
    }

    public function handle(Questionnaire $questionnaire, Exporter $export, ExportLogger $logger, Dispatcher $events, User $user)
    {
        try{
            $logger->start($this->id);

            $survey = $questionnaire->find($this->id);

            $user = $user->find($this->userid);

            $filename = $export->generate($survey, $this->filters);

            $logger->stop($survey);

            \Event::fire('rapport.generated', [$user, $survey, $filename]);

            $this->job->delete();
        }
        catch(Exception $e)
        {
            $logger->error($e);

            \Event::fire('rapport.failed', [$user, $survey]);
        }
    }

}