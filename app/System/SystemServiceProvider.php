<?php

namespace App\System;

use Illuminate\Support\ServiceProvider;

class SystemServiceProvider extends ServiceProvider
{
    public function boot()
    {
        include_once __DIR__.'/helpers.php';

        $this->bladeDirectives();

        if (env('APP_ENV') == 'production') {
            $this->app['newrelic']->setAppName('app.zichtopmantelzorg.be');
        } else {
            $this->app['newrelic']->setAppName(env('APP_ENV').'.zichtopmantelzorg.be');
        }
    }

    public function bladeDirectives()
    {
        //@todo error directive debugging
        //for some reason this directive fails to work when it's put in the main view file
        //using this directive should always happen in a blade @include(ed) file
        //need to check why this happens.
        \Blade::directive('error', function ($field) {
            $field = trim($field, "(')");
            $error_class = str_replace('_', '-', $field);

            $template = <<<HTML
<?php if(isset(\$errors) && \$errors->has('$field')): ?>
    <div class="alert alert-danger error-$error_class">{{ \$errors->first('$field') }}</div>
<?php endif; ?>
HTML;
            return $template;
        });
    }

    public function register()
    {
        $this->app->bind('ui', 'App\System\UI');
    }
}
