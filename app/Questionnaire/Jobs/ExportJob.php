<?php

namespace App\Questionnaire\Jobs;

use App\Commands\Command;
use App\Questionnaire\Export\Exporter;
use App\Questionnaire\Export\ExportLogger;
use App\Questionnaire\Questionnaire;
use App\User;
use Exception;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;

class ExportJob extends Command implements ShouldQueue, SelfHandling
{
    use InteractsWithQueue;

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
        try {
            $logger->start($this->id);

            $survey = $questionnaire->find($this->id);

            $user = $user->find($this->userid);

            $report = $export->generate($survey, $this->filters);

            $logger->stop($survey);

            $events->fire('rapport.generated', [$user, $survey, $report]);

            $this->job->delete();
        } catch (Exception $e) {
            $logger->error($e);

            $events->fire('rapport.failed', [$user, $survey]);
        }
    }
}
