<?php

namespace Questionnaire;

use Carbon\Carbon;
use Questionnaire\Export\CsvExport;
use Questionnaire\Export\DataHandler;
use Questionnaire\Export\ExportLogger;
use Questionnaire\Export\Repository;
use Questionnaire\Jobs\ExportJob;

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

        $this->app['Questionnaire\Jobs\ExportJob'] = $this->app->share(function ($app) {

            $logger = new ExportLogger($app['log'], $app['db']);

            return new ExportJob(new Questionnaire(), $app['Questionnaire\Export\Exporter'], $logger, $app['events'], new \User());
        });

        $this->app['Questionnaire\Export\Exporter'] = $this->app->share(function ($app) {

            return new CsvExport($app['excel'], new Carbon(), $app['Questionnaire\Export\DataHandler']);
        });

        $this->app['Questionnaire\Export\DataHandler'] = $this->app->share(function($app)
        {
            $repo = new Repository(new Answer, new Choise, $app['db']->connection());

            return new DataHandler($repo);
        });

        $commands = [
            'Questionnaire\Console\Export'
        ];

        $this->commands($commands);
    }
}