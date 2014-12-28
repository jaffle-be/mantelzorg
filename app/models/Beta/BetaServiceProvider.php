<?php namespace Beta;

use Illuminate\Support\ServiceProvider;
use Search\SearchServiceInterface;

class BetaServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSearchIndexer();
    }

    private function registerSearchIndexer()
    {
        /** @var SearchServiceInterface $search */
        $search = $this->app->make('Search\SearchServiceInterface');

        $search->addAutoIndexing(new Registration());
    }
}