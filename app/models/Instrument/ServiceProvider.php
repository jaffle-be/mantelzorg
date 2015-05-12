<?php
namespace Instrument;

use Mantelzorger\Mantelzorger;
use Mantelzorger\Oudere;
use Questionnaire\Answer;
use Questionnaire\Question;
use Questionnaire\Session;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register()
    {
        $this->app['instrument.memorize'] = $this->app->share(function () {
            return new Memorize\Questionnaire(new Answer(), new Question(), new Mantelzorger(), new Oudere(), new Session());
        });
    }
}