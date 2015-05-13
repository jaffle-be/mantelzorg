<?php namespace Organisation;

use Illuminate\Support\ServiceProvider;

class OrganisationServiceProvider extends ServiceProvider{

    public function register()
    {
        $this->app->bind('Organisation\OrganisationRepositoryInterface', 'Organisation\OrganisationRepository');
    }

}