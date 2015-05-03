<?php namespace Notifications\Rapport;

use Illuminate\Log\Writer;
use Notifications\EmailNotifier;
use Questionnaire\Questionnaire;
use User;

class Generated
{

    protected $email;

    public function __construct(EmailNotifier $email, Writer $log)
    {
        $this->email = $email;
        $this->log = $log;
    }

    public function handle(User $user, Questionnaire $survey, $filename)
    {
//        $this->email->notify($user, 'rapport.generated', [
//            'user'     => $user,
//            'survey'   => $survey,
//            'filename' => $filename
//        ]);
    }
}