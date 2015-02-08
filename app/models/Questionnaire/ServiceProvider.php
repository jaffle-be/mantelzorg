<?php

namespace Questionnaire;

use Questionnaire\Export\CsvExport;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->app['events']->subscribe(new EventSubscriber(new Questionnaire, new Panel, new Question, new Choise, new Answer));

        Questionnaire::observe($this->app['Questionnaire\Observer\Questionnaire']);

        Question::observe($this->app['Questionnaire\Observer\Question']);

        Session::observe($this->app['Questionnaire\Observer\Session']);

        Answer::observe($this->app['Questionnaire\Observer\Answer']);
    }

    public function register()
    {
        $this->app['Questionnaire\Observer\Questionnaire'] = new Observer\Questionnaire($this->app['events']);

        $this->app['Questionnaire\Observer\Question'] = new Observer\Question($this->app['events'], new Question());

        $this->app['Questionnaire\Observer\Session'] = new Observer\Session($this->app['events']);

        $this->app['Questionnaire\Observer\Answer'] = new Observer\Answer($this->app['events']);

        $this->app['Questionnaire\Export\Exporter'] = $this->app->share(function ($app) {
            return new CsvExport($app['excel']);
        });
    }
}