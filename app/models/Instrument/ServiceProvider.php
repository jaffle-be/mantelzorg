<?php
namespace App\Instrument;

use \App\Mantelzorger\Mantelzorger;
use \App\Mantelzorger\Oudere;
use App\Questionnaire\Answer;
use App\Questionnaire\Question;
use App\Questionnaire\Session;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register()
    {
        $this->app['instrument.memorize'] = $this->app->share(function () {
            return new Memorize\Questionnaire(new Answer(), new Question(), new Mantelzorger(), new Oudere(), new Session());
        });
    }
}