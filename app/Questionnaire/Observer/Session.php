<?php

namespace App\Questionnaire\Observer;

use App\Questionnaire\Session as Model;
use Illuminate\Events\Dispatcher;

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
