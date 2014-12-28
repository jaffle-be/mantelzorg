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

        $this->registerSearchIndexer();
    }

    public function boot()
    {
        Mantelzorger::observe($this->app['Mantelzorger\Observer\Mantelzorger']);

        Oudere::observe($this->app['Mantelzorger\Observer\Oudere']);
    }

    private function registerSearchIndexer()
    {
        /** @var SearchServiceInterface $search */
        $search = $this->app->make('Search\SearchServiceInterface');

        $search->addAutoIndexing(new Mantelzorger());
    }
}