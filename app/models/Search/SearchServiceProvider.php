<?php


namespace Search;

use Elasticsearch\Client;
use Illuminate\Support\ServiceProvider;
use Mustache_Engine;

class SearchServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //keep this in the boot section so we bind to the event dispatcher actually used in the eloquent model instances.
        $this->app['Search\SearchServiceInterface']->boot();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerService();

        $this->app->bind('Search\SearchServiceInterface', 'Search\SearchService');

        $this->registerCommands();
    }

    private function registerService()
    {
        $this->app['Search\SearchService'] = $this->app->share(function ($app) {
            $config = $app->make('config');

            $config = $config->get('elasticsearch');

            $client = new Client(array_only($config, ['hosts']));

            $service = new SearchService($app, $client, $config);

            return $service;
        });
    }

    private function registerCommands()
    {
        $this->commands(['Search\Command\BuildIndexes', 'Search\Command\FlushType', 'Search\Command\UpdateSettings', 'Search\Command\UpdateMapping']);
    }
}