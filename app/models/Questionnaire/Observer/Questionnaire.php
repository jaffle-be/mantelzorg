<?php

namespace Questionnaire\Observer;

use Illuminate\Events\Dispatcher;

class Questionnaire {

    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

} 