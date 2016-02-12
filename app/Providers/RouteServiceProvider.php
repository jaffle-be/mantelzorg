<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        $router->pattern('id', '\d+');

        $router->pattern('hulpverlener', '\d+');
        $router->model('hulpverlener', 'App\User');

        $router->pattern('mantelzorger', '\d+');
        $router->model('mantelzorger', 'App\Mantelzorger\Mantelzorger');

        $router->pattern('mantelzorgers', '\d+');
        $router->model('mantelzorgers', 'App\Mantelzorger\Mantelzorger');

        $router->pattern('oudere', '\d+');
        $router->model('oudere', 'App\Mantelzorger\Oudere');

        $router->pattern('survey', '\d+');
        $router->model('survey', 'App\Questionnaire\Questionnaire');

        $router->pattern('panel', '\d+');
        $router->model('panel', 'App\Questionnaire\Panel');

        $router->pattern('question', '\d+');
        $router->model('question', 'App\Questionnaire\Question');

        $router->pattern('session', '\d+');
        $router->model('session', 'App\Questionnaire\Session');

        $router->pattern('choise', '\d+');
        $router->model('choise', 'App\Questionnaire\Choise');

        $router->pattern('report', '\d+');
        $router->model('report', 'App\Questionnaire\Export\Report');

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
