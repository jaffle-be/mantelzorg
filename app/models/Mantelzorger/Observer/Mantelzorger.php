<?php

namespace Mantelzorger\Observer;

use Illuminate\Events\Dispatcher;

/**
 * Class Mantelzorger
 * @package Mantelzorger\Observer
 */
class Mantelzorger {

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
        if(empty($model->email))
        {
            $model->email = null;
        }
    }



} 