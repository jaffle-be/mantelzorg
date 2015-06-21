<?php

namespace App\Mantelzorger\Observer;

use Illuminate\Events\Dispatcher;

/**
 * Class App\Mantelzorger
 *
 * @package App\Mantelzorger\Observer
 */
class Oudere
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

    /**
     *
     */
    public function updating($model)
    {
        if (empty($model->email)) {
            $model->email = null;
        }
    }
}