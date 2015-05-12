<?php namespace Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider{

    public function boot()
    {
        $this->app['events']->listen('rapport.generated', 'Notifications\Rapport\Generated');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['Notifications\EmailNotifier'] = $this->app->share(function($app){
            return new EmailNotifier($app['mailer'], $app['translator']);
        });
    }

    public function provides()
    {
        return array('Notifications\EmailNotifier');
    }
}