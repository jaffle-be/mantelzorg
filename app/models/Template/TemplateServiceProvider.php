<?php

namespace Template;

class TemplateServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['template'] = $this->app->share(function ($app) {
            $crumb = new Breadcrumb();

            return new TemplateRepository($crumb);
        });
    }
}