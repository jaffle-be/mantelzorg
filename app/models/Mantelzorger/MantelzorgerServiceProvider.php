<?php

namespace App\Mantelzorger;

use Illuminate\Support\ServiceProvider;
use App\Search\SearchServiceInterface;

class MantelzorgerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app['App\Mantelzorger\Observer\Oudere'] = new Observer\Oudere($this->app['events']);

        $this->app['App\Mantelzorger\Observer\App\Mantelzorger'] = new Observer\Mantelzorger($this->app['events']);
    }

    public function boot()
    {
        Mantelzorger::observe($this->app['App\Mantelzorger\Observer\App\Mantelzorger']);

        Oudere::observe($this->app['App\Mantelzorger\Observer\Oudere']);
    }
}