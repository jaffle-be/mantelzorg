<?php

namespace App\Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app['events']->listen('rapport.generated', 'App\Notifications\Rapport\Generated');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app['App\Notifications\EmailNotifier'] = $this->app->share(function ($app) {
            return new EmailNotifier($app['mailer'], $app['translator']);
        });
    }

    public function provides()
    {
        return array('App\Notifications\EmailNotifier');
    }
}
