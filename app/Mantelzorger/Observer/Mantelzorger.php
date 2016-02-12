<?php

namespace App\Mantelzorger\Observer;

use App\Mantelzorger\Mantelzorger as Model;
use Illuminate\Events\Dispatcher;

/**
 * Class App\Mantelzorger.
 */
class Mantelzorger
{
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * @param Dispatcher $events
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    public function updating($model)
    {
        if (empty($model->email)) {
            $model->email = null;
        }
    }

    public function deleting(Model $model)
    {
        foreach ($model->surveys as $survey) {
            $survey->delete();
        }

        foreach ($model->oudere as $oudere) {
            $oudere->delete();
        }
    }
}
