<?php


class UserServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register()
    {
        $this->app['UserMailer'] = $this->app->share(function ($app)
        {
            return new UserMailer($app['mailer']);
        });

        $this->app['events']->listen('user.password-generated', 'UserMailer@passwordGenerated');

        $this->registerSearchIndexer();
    }

    public function boot()
    {
        User::observe($this->app->make('UserObserver'));
    }

    private function registerSearchIndexer()
    {
        /** @var SearchServiceInterface $search */
        $search = $this->app->make('Search\SearchServiceInterface');

        $search->addAutoIndexing(new User());
    }

} 