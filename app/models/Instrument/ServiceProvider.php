<?php
namespace Instrument;

class ServiceProvider extends \Illuminate\Support\ServiceProvider{

    public function register()
    {
        $this->app['instrument.memorize'] = $this->app->share(function($app)
        {
            return new Memorize\Questionnaire($app['session.store']);
        });

        $this->app['instrument.engine'] = $this->app->share(function($app){
            return new Engine\Template(new Engine\Header, new Engine\Question);
        });
    }

} 