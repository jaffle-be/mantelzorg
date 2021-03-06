<?php

namespace App\Notifications\Rapport;

use App\Notifications\EmailNotifier;
use App\Questionnaire\Export\Report;
use App\Questionnaire\Questionnaire;
use App\User;
use Illuminate\Log\Writer;

class Generated
{
    protected $email;

    public function __construct(EmailNotifier $email, Writer $log)
    {
        $this->email = $email;
        $this->log = $log;
    }

    public function handle(User $user, Questionnaire $survey, Report $report)
    {
        $this->email->notify($user, 'rapport.generated', [
            'user' => $user,
            'survey' => $survey,
            'report' => $report,
        ]);
    }
}
