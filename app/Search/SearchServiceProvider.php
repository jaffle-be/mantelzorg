<?php


namespace App\Search;

use Elasticsearch\Client;
use Illuminate\Support\ServiceProvider;
use Mustache_Engine;

class SearchServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //keep this in the boot section so we bind to the event dispatcher actually used in the eloquent model instances.
        $this->app['App\Search\SearchServiceInterface']->boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerService();

        $this->app->bind('App\Search\SearchServiceInterface', 'App\Search\SearchService');

        $this->registerCommands();
    }

    private function registerService()
    {
        $this->app['App\Search\SearchService'] = $this->app->share(function ($app) {
            $config = $app->make('config');

            $config = $config->get('elasticsearch');

            $client = new Client(array_only($config, ['hosts']));

            $service = new SearchService($app, $client, $config);

            return $service;
        });
    }

    private function registerCommands()
    {
        $this->commands(['App\Search\Command\BuildIndexes', 'App\Search\Command\FlushType', 'App\Search\Command\UpdateSettings', 'App\Search\Command\UpdateMapping']);
    }
}