<?php

namespace Questionnaire;

class ServiceProvider extends \Illuminate\Support\ServiceProvider{

    public function register()
    {
        $this->app['events']->subscribe(new EventSubscriber(new Questionnaire, new Panel, new Question, new Choise, new Answer));
    }

} 