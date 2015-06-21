<?php


namespace App\Questionnaire\Observer;

use Illuminate\Events\Dispatcher;
use App\Questionnaire\Session as Model;

class Session
{

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function deleting(Model $model)
    {
        foreach ($model->answers as $answer) {
            $answer->delete();
        }
    }
}