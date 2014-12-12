<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::action('IndexController@getLogin');

    $user = Auth::user();

    if($user->active === '0')
    {
        return Redirect::action('IndexController@getLogout');
    }
});

Route::filter('auth.admin', function()
{
    if (Auth::guest()) return Redirect::action('IndexController@getLogin');

    $user = Auth::user();

    if($user->admin === '0')
    {
        return Redirect::route('dash')->with('message', Lang::get('master.info.no-right-to-section'));
    }

});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/**
 * Controleer of de gebruiker de mantelzorgers mag inzien voor een bepaalde hulpverlener
 */
Route::filter('mantelzorgers', function(\Illuminate\Routing\Route $route, $request)
{
    $hulpverlener = $route->getParameter('hulpverlener');

    $user = Auth::user();

    //indien de gebruiker geen admin is en hij kijkt naar mantelzorgers van iemand anders
    if($user->admin === '0' && $user->id !== $hulpverlener->id)
    {
        return Redirect::route('instellingen.index');
    }
});