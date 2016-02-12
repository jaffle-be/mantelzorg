<?php

namespace App\Questionnaire\Observer;

use Illuminate\Events\Dispatcher;

class Answer
{
    /**
     * @var Dispatcher
     */
    protected $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function deleting($model)
    {
        $model->choises()->sync([]);
    }
}
