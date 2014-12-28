<?php

class LocationController extends AdminController
{

    /**
     * @var Organisation\Location
     */
    protected $location;

    public function __construct(\Organisation\Location $location)
    {
        $this->location = $location;

        $this->beforeFilter('auth.admin');
    }

    public function store()
    {
        $validator = $this->location->validator();

        if ($validator->fails()) {
            return array(
                'status' => 'error',
                'errors' => $validator->messages()->toArray()
            );
        } else {
            $location = $this->location->create(Input::all());

            return array(
                'status'   => 'oke',
                'location' => $location->toArray()
            );
        }
    }
}