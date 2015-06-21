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
		Route::pattern('id', '\d+');

		Route::pattern('hulpverlener', '\d+');
		Route::model('hulpverlener', 'App\User');

		Route::pattern('mantelzorger', '\d+');
		Route::model('mantelzorger', 'App\Mantelzorger\Mantelzorger');

		Route::pattern('questionnaire', '\d+');
		Route::model('questionnaire', 'App\Questionnaire\Questionnaire');

		Route::pattern('panel', '\d+');
		Route::model('panel', 'App\Questionnaire\Panel');

		Route::pattern('question', '\d+');
		Route::model('question', 'App\Questionnaire\Question');

		Route::pattern('survey', '\d+');
		Route::model('survey', 'App\Questionnaire\Session');
		
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
