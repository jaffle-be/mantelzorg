<?php

namespace Template;

class TemplateServiceProvider extends \Illuminate\Support\ServiceProvider{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['template'] = $this->app->share(function($app)
        {
            $sidebar = $app['config']->get('sidebar');

            $sidebar = new Sidebar($sidebar);

            $crumb = new Breadcrumb();

            return new TemplateRepository($sidebar, $crumb);
        });
    }


} 