<?php namespace UI;

use Illuminate\Support\ServiceProvider;

class UIServiceProvider extends ServiceProvider{

    public function boot()
    {

    }

    public function register()
    {
        $this->app->bind('ui', 'UI\UI');
    }

}