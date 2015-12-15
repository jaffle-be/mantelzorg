<?php

namespace App\Http\Controllers;

use App\Organisation\Organisation;
use Illuminate\Contracts\Validation\Factory;
use Input;

class OrganisationController extends AdminController
{
    /**
     * @var \App\Organisation\Organisation
     */
    protected $organisation;

    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;

        $this->middleware('auth.admin');
    }

    public function store(Factory $validator)
    {
        $name = Input::get('name');

        $validator = $validator->make(Input::all(), $this->organisation->rules());

        if ($validator->fails()) {
            return array(
                'status' => 'failed',
                'errors' => $validator->messages()->toArray(),
            );
        } else {
            $organisation = $this->organisation->create(array(
                'name' => $name,
            ));

            return array(
                'status' => 'oke',
                'organisation' => $organisation->toArray(),
            );
        }
    }
}
