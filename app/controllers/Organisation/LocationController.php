<?php
namespace App\Organisation;

use AdminController;
use Input;

class LocationController extends AdminController
{

    /**
     * @var \App\Organisation\Location
     */
    protected $location;

    protected $organisation;

    public function __construct(\App\Organisation\Organisation $organisation, \App\Organisation\Location $location)
    {
        $this->organisation = $organisation;

        $this->location = $location;
    }

    public function index($organisationid)
    {
        $organisation = $this->organisation->with(array('locations'))->find($organisationid);

        return $organisation->locations->toJson();
    }

    public function store($organisationid)
    {
        $input = Input::all();

        $input['organisation_id'] = $organisationid;

        $validator = $this->location->validator(null, $input);

        if ($validator->fails()) {
            return array(
                'status' => 'error',
                'errors' => $validator->messages()->toArray()
            );
        } else {
            $location = $this->location->create($input);

            return array(
                'status'   => 'oke',
                'location' => $location->toArray()
            );
        }
    }
}