<?php

namespace App\Search;

use App\System\ServiceProvider;
use Elasticsearch\ClientBuilder;

class SearchServiceProvider extends ServiceProvider
{
    protected $namespace = 'search';

    protected function observers()
    {
    }

    protected function listeners()
    {
        //keep this in the boot section so we bind to the event dispatcher actually used in the eloquent model instances.
        $this->app['App\Search\SearchServiceInterface']->boot();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerService();

        $this->app->bind('App\Search\SearchServiceInterface', 'App\Search\SearchService');

        $this->registerCommands();
    }

    protected function registerService()
    {
        $this->app['App\Search\SearchService'] = $this->app->share(function ($app) {

            $config = new Config(config('search'));

            $client = ClientBuilder::create()
                ->setHosts(config('search.hosts'))
                ->build();

            $service = new SearchService($app, $client, $config);

            return $service;
        });
    }

    protected function registerCommands()
    {
        $this->commands(['App\Search\Command\BuildIndexes', 'App\Search\Command\FlushType', 'App\Search\Command\UpdateSettings', 'App\Search\Command\UpdateMapping']);
    }
}