<?php

namespace App\Mantelzorger\Observer;

use Illuminate\Events\Dispatcher;
use \App\Mantelzorger\Mantelzorger as Model;

/**
 * Class App\Mantelzorger
 *
 * @package App\Mantelzorger\Observer
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