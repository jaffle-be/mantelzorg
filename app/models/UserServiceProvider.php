<?php

use Search\SearchServiceInterface;

class UserServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register()
    {
        $this->app['UserMailer'] = $this->app->share(function ($app) {
            return new UserMailer($app['mailer']);
        });

        $this->app['events']->listen('user.password-generated', 'UserMailer@passwordGenerated');
    }

    public function boot()
    {
        User::observe($this->app->make('UserObserver'));
    }
}