<?php namespace App\System;

use Illuminate\Support\ServiceProvider;

class UIServiceProvider extends ServiceProvider{

    public function boot()
    {

    }

    public function register()
    {
        $this->app->bind('ui', 'App\System\UI');
    }

}