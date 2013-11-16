<?php
use Organisation\Organisation;

class OrganisationController extends AdminController{

    /**
     * @var Organisation\Organisation
     */
    protected $organisation;

    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;

        $this->beforeFilter('auth.admin');
    }

    public function store()
    {
        $name = Input::get('name');

        $validator = $this->organisation->validator();

        if($validator->fails())
        {
            return array(
                'status' => 'failed',
                'errors' => $validator->messages()->toArray()
            );
        }
        else
        {
            $organisation = $this->organisation->create(array(
                'name' => $name
            ));

            return array(
                'status' => 'oke',
                'organisation' => $organisation->toArray()
            );
        }

    }

} 