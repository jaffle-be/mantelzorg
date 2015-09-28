<?php

namespace App\Questionnaire;

use App\Questionnaire\Export\CsvExport;
use App\Questionnaire\Export\DataHandler;
use App\Questionnaire\Export\ExportLogger;
use App\Questionnaire\Export\Report;
use App\Questionnaire\Export\Repository;
use App\Questionnaire\Jobs\ExportJob;
use Carbon\Carbon;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        $this->app['events']->subscribe(new EventSubscriber(new Questionnaire, new Panel, new Question, new Choise, new Answer));

        Questionnaire::observe($this->app['App\Questionnaire\Observer\App\Questionnaire']);

        Question::observe($this->app['App\Questionnaire\Observer\Question']);

        Session::observe($this->app['App\Questionnaire\Observer\Session']);

        Answer::observe($this->app['App\Questionnaire\Observer\Answer']);
    }

    public function register()
    {
        $this->app['App\Questionnaire\Observer\App\Questionnaire'] = new Observer\Questionnaire($this->app['events']);

        $this->app['App\Questionnaire\Observer\Question'] = new Observer\Question($this->app['events'], new Question());

        $this->app['App\Questionnaire\Observer\Session'] = new Observer\Session($this->app['events']);

        $this->app['App\Questionnaire\Observer\Answer'] = new Observer\Answer($this->app['events']);

        $this->app['App\Questionnaire\Jobs\ExportJob'] = $this->app->share(function ($app) {

            $logger = new ExportLogger($app['log'], $app['db']);

            return new ExportJob(new Questionnaire(), $app['App\Questionnaire\Export\Exporter'], $logger, $app['events'], new \App\User());
        });

        $this->app['App\Questionnaire\Export\Exporter'] = $this->app->share(function ($app) {

            return new CsvExport($app['App\Questionnaire\Export\SessionFilter'], $app['excel'], new Carbon(), $app['App\Questionnaire\Export\DataHandler'], new Report());
        });

        $this->app['App\Questionnaire\Export\DataHandler'] = $this->app->share(function($app)
        {
            $repo = new Repository(new Answer, new Choise, $app['db']->connection());

            return new DataHandler($repo);
        });

        $commands = [
            'App\Questionnaire\Console\Export'
        ];

        $this->commands($commands);
    }
}