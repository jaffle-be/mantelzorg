<?php

namespace App;

use App\User;
use Illuminate\Events\Dispatcher;

class UserObserver
{

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function deleting(User $model)
    {
        foreach ($model->mantelzorgers as $mantelzorger) {
            $mantelzorger->delete();
        }
    }
}