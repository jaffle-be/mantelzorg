<?php

namespace Questionnaire\Observer;

use Illuminate\Events\Dispatcher;

class Question
{

    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function saving($model)
    {
        if ($model->isDirty('multiple_choise') && $model->multiple_choise == '0') {
            $model->multiple_answer = 0;
        }
    }
}