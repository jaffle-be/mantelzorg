<?php

namespace App;

class UserServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register()
    {
        $this->app['App\UserMailer'] = $this->app->share(function ($app) {
            return new UserMailer($app['mailer']);
        });

        $this->app['events']->listen('user.password-generated', 'App\UserMailer@passwordGenerated');

        $this->app->bind('App\UserRepositoryInterface', 'App\UserRepository');
    }

    public function boot()
    {
        User::observe($this->app->make('App\UserObserver'));
    }
}