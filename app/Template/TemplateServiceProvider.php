<?php

namespace App\Template;

class TemplateServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app['template'] = $this->app->share(function () {
            $crumb = new Breadcrumb();

            return new TemplateRepository($crumb);
        });
    }
}
