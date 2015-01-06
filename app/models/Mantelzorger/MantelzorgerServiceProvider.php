<?php

namespace Mantelzorger;

use Illuminate\Support\ServiceProvider;
use Search\SearchServiceInterface;

class MantelzorgerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app['Mantelzorger\Observer\Oudere'] = new Observer\Oudere($this->app['events']);

        $this->app['Mantelzorger\Observer\Mantelzorger'] = new Observer\Mantelzorger($this->app['events']);
    }

    public function boot()
    {
        Mantelzorger::observe($this->app['Mantelzorger\Observer\Mantelzorger']);

        Oudere::observe($this->app['Mantelzorger\Observer\Oudere']);
    }
}