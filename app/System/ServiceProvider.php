<?php namespace App\System;

use Illuminate\Support\ServiceProvider as Provider;
use ReflectionClass;

abstract class ServiceProvider extends Provider
{

    protected $namespace = false;

    protected $setups = [
        'helpers', 'publish', 'config', 'routes', 'views', 'lang'
    ];

    protected $publishing = [
        '/config',
        '/database/migrations',
        '/database/seeds',
        '/database/images',
    ];

    public function boot()
    {
        if (!$this->namespace) {
            throw new \Exception('Please set the namespace for the provider');
        }

        $dir = $this->dir();

        foreach ($this->setups as $setup) {
            $this->$setup($dir);
        }

        $this->observers();
        $this->listeners();
    }

    protected abstract function observers();

    protected abstract function listeners();

    /**
     * @param $dir
     */
    protected function routes($dir)
    {
        $routes = $dir . '/Http/routes.php';
        if (file_exists($routes)) {
            include $routes;
        }

        /**
         * we bind the loading of the breadcrumbs to the bootstrapped event, just after all providers have been booted.
         * why? we need to make sure that our breadcrumbs use the config value fetched from the database.
         * In the boot method, the connection isn't available yet.
         */
        $this->app->booted(function() use ($dir)
        {
            $breadcrumbs = $dir . '/Http/breadcrumbs.php';

            if(file_exists($breadcrumbs))
            {
                include $breadcrumbs;
            }
        });
    }

    /**
     * @param $dir
     */
    protected function publish($dir)
    {
        $publish = [];

        array_walk($this->publishing, function ($value) use ($dir, &$publish) {

            $key = $dir . $value;

            if (file_exists($key) || is_dir($key)) {
                $value = base_path(ltrim($value, '/'));
                $publish[$key] = $value;
            }
        });

        $this->publishes($publish);
    }

    /**
     * @param $dir
     */
    protected function views($dir)
    {
        $views = $dir . '/resources/views/';
        if (file_exists($views)) {
            $this->loadViewsFrom($views, $this->namespace);
        }
    }

    /**
     * @param $dir
     */
    protected function lang($dir)
    {
        $lang = $dir . '/resources/lang';
        if (file_exists($lang)) {
            $this->loadTranslationsFrom($lang, $this->namespace);
        }
    }

    /**
     * @param $dir
     */
    protected function config($dir)
    {
        $config = $dir . '/config/' . $this->namespace . '.php';

        if (file_exists($config)) {
            $this->mergeConfigFrom($config, $this->namespace);
        }
    }

    /**
     * @return string
     */
    protected function dir()
    {
        $reflection = new ReflectionClass($this);
        $dir = dirname($reflection->getFileName());

        return $dir;
    }

    protected function helpers($dir)
    {
        $helpers = $dir . '/helpers.php';

        if (file_exists($helpers)) {
            include_once $helpers;
        }
    }
}