<?php

namespace App\Organisation;

class OrganisationRepository implements OrganisationRepositoryInterface
{
    protected $organisation;

    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    public function getForSelect()
    {
        return $this->organisation->pluck('name', 'id')->all();
    }
}
