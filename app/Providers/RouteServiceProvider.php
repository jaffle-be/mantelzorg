<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

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
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		$router->pattern('id', '\d+');

		$router->pattern('hulpverlener', '\d+');
		$router->model('hulpverlener', 'App\User');

		$router->pattern('mantelzorger', '\d+');
		$router->model('mantelzorger', 'App\Mantelzorger\Mantelzorger');

		$router->pattern('questionnaire', '\d+');
		$router->model('questionnaire', 'App\Questionnaire\Questionnaire');

		$router->pattern('panel', '\d+');
		$router->model('panel', 'App\Questionnaire\Panel');

		$router->pattern('question', '\d+');
		$router->model('question', 'App\Questionnaire\Question');

		$router->pattern('survey', '\d+');
		$router->model('survey', 'App\Questionnaire\Session');
		
		parent::boot($router);
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			require app_path('Http/routes.php');
		});
	}

}
