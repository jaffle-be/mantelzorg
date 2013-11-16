<?php

use Organisation\Organisation;

class HulpverlenerController extends AdminController{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Organisation\Organisation
     */
    protected $organisation;

    public function __construct(User $user, Organisation $organisation)
    {
        $this->page = 'hulpverlener';

        $this->user = $user;

        $this->organisation = $organisation;

        $this->beforeFilter('auth.admin');
    }

    public function index()
    {
        $users = $this->user->with('organisation')->get();

        $this->layout->content = View::make('hulpverlener.index', compact(array('users')))
            ->nest('subnav', 'layout.admin.subnavs.users', array('page' => $this->page));
    }

    public function edit($id)
    {
        $user = $this->user->find($id);

        if($user)
        {
            $organisations = $this->organisation->orderBy('name')->get();

            //create an array that has has en empty first value, then all the organisations, then a 'create new' option
            //empty has no value, organisations have their id as value, new has 'new' as value
            $organisations = array('' => Lang::get('users.pick_organisation')) +
                $organisations->lists('name', 'id') +
                array('new' => Lang::get('users.new_organisation'));

            //prepare an array of locations
            $locations = $user->organisation->locations->lists('name', 'id');
            $locations = array('' => Lang::get('users.pick_location'))
                + $locations + array('new' => Lang::get('users.new_location'));

            $this->layout->content = View::make('hulpverlener.edit', compact(array('user', 'organisations', 'locations')))
                ->nest('subnav', 'layout.admin.subnavs.users', array('page' => $this->page))
                ->nest('creatorOrganisations', 'modals.organisation-creator', compact(array('inschrijving')))
                ->nest('creatorLocations', 'modals.location-creator', compact(array('inschrijving')));
        }

        else
        {
            return Redirect::action('HulpverlenerController@index');
        }

    }

    public function update($id)
    {
        $user = $this->user->find($id);

        if(!$user)
        {
            return Redirect::action('HulpverlenerController@index');
        }

        $input = Input::all();

        if(!isset($input['active']))
        {
            $input['active'] = '0';
        }
        if(!isset($input['admin']))
        {
            $input['admin'] = '0';
        }

        $validator = $this->user->validator(array(
            'firstname', 'lastname', 'male', 'phone',
            'organisation_id', 'organisation_location_id',
            'admin', 'active'
        ), $input);

        $validator->sometimes('email', 'required|email|unique:users', function($input) use ($user)
        {
            return $user->email !== $input->email;
        });

        if($validator->fails())
        {
            return Redirect::back()->withErrors($validator->messages())->withInput($input);
        }

        else
        {
            $user->update($input);

            return Redirect::action('HulpverlenerController@index');
        }

    }

}