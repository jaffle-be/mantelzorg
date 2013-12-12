<?php

namespace Questionnaire;

class ServiceProvider extends \Illuminate\Support\ServiceProvider{

    public function boot()
    {
        $this->app['events']->subscribe(new EventSubscriber(new Questionnaire, new Panel, new Question, new Choise, new Answer));

        Questionnaire::observe(new Observer\Questionnaire($this->app['events']));
    }

    public function register()
    {
    }

} 