<?php namespace App\System;

use Illuminate\Support\ServiceProvider;

class SystemServiceProvider extends ServiceProvider{

    public function boot()
    {
        include_once(__DIR__ . '/helpers.php');
    }

    public function register()
    {
        $this->app->bind('ui', 'App\System\UI');
    }

}