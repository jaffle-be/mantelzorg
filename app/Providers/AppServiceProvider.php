<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use Route;
use Session;
use Auth;
use UI;
use Validator;
use Hash;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('layout.*', function ($view) {
            $view->with('user', Auth::user());
        });

        Validator::extend('passcheck', function ($attribute, $value, $parameters) {

            $user = Auth::user();

            return Hash::check($value, $user->password);

        });

        View::composer('layout.messages', function ($view) {
            $message = Session::has('message') ? Session::get('message') : null;

            $error = Session::has('error') ? Session::get('error') : null;

            $success = Session::has('success') ? Session::get('success') : null;

            $view->with(compact('message', 'error', 'success'));
        });

        View::composer('*', function ($view) {
            /** @var \Illuminate\Routing\Route $route */
            $route = Route::getCurrentRoute();
            //Add zero padding to the interface for better UX on tablets.
            $view->with(['fullScreen' => UI::isMobile() || UI::isTablet()]);
        });
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );
    }
}
