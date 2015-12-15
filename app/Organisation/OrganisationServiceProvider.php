<?php

namespace App\Organisation;

use Illuminate\Support\ServiceProvider;

class OrganisationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('App\Organisation\OrganisationRepositoryInterface', 'App\Organisation\OrganisationRepository');
    }
}
