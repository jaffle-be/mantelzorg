<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Factory;
use Input;

class LocationController extends AdminController
{
    /**
     * @var \App\Organisation\Location
     */
    protected $location;

    public function __construct(\App\Organisation\Location $location)
    {
        $this->location = $location;

        $this->middleware('auth.admin');
    }

    public function store(Factory $validator)
    {
        $validator = $validator->make(Input::all(), $this->location->rules());

        if ($validator->fails()) {
            return array(
                'status' => 'error',
                'errors' => $validator->messages()->toArray(),
            );
        } else {
            $location = $this->location->create(Input::all());

            return array(
                'status' => 'oke',
                'location' => $location->toArray(),
            );
        }
    }
}
