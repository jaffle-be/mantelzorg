<?php

namespace Questionnaire;

class ServiceProvider extends \Illuminate\Support\ServiceProvider{

    public function boot()
    {
        $this->app['events']->subscribe(new EventSubscriber(new Questionnaire, new Panel, new Question, new Choise, new Answer));

        Questionnaire::observe($this->app['Questionnaire\Observer\Questionnaire']);

        Question::observe($this->app['Questionnaire\Observer\Question']);
    }

    public function register()
    {
        $this->app['Questionnaire\Observer\Questionnaire'] = new Observer\Questionnaire($this->app['events']);

        $this->app['Questionnaire\Observer\Question'] = new Observer\Question($this->app['events']);
    }

} 