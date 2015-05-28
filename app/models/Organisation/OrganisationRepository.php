<?php namespace Organisation;

class OrganisationRepository implements OrganisationRepositoryInterface{

    protected $organisation;

    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    public function getForSelect()
    {
        return $this->organisation->orderBy('name')->lists('name', 'id');
    }
}