<?php

use Organisation\Organisation;
use Organisation\Location;

class HulpverlenerController extends AdminController{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Organisation\Organisation
     */
    protected $organisation;

    /**
     * @var Organisation\Location
     */
    protected $location;

    public function __construct(User $user, Organisation $organisation, Location $location)
    {
        $this->user = $user;

        $this->organisation = $organisation;

        $this->location = $location;

        $this->beforeFilter('auth.admin');
    }

    public function index()
    {
        $users = $this->user->with('organisation')->paginate();

        $this->layout->content = View::make('hulpverlener.index', compact(array('users')));
    }

    public function edit($id)
    {
        $user = $this->user->find($id);

        if($user)
        {
            $organisations = $this->organisation->orderBy('name')->get();

            /**
             * create an array that has has en empty first value, then all the organisations, then a 'create new' option
             * empty has no value, organisations have their id as value, new has 'new' as value
             */
            $organisations = array('' => Lang::get('users.pick_organisation')) +
                $organisations->lists('name', 'id') +
                array('new' => Lang::get('users.new_organisation'));

            /**
             * if the user had switched the organisation, you need to load
             * the locations that are linked to that organisation
             */

            if(Input::old() && $user->organisation_id !== Input::old('organisation_id'))
            {
                $locations = $this->location->where('organisation_id', Input::old('organisation_id'))
                    ->orderBy('name')
                    ->get()
                    ->lists('name', 'id');
            }
            else if($user->organisation)
            {
                $locations = $user->organisation->locations()
                    ->orderBy('name')
                    ->get()
                    ->lists('name', 'id');
            }
            else{
                $locations = array();
            }
            $locations = array('' => Lang::get('users.pick_location'))
                + $locations + array('new' => Lang::get('users.new_location'));


            $this->layout->content = View::make('hulpverlener.edit', compact(array('user', 'organisations', 'locations')))
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

        //validation rule if email was changed
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

    /**
     * Get an array of ids to use to regen the passwords.
     */
    public function regenPasswords()
    {
        $input = Input::get('ids');

        if(!is_array($input) || empty($input))
        {
            return json_encode(array(
                'status' => 'no decent input provided.'
            ));
        }

        $users = $this->user->whereIn('id', $input)->get();

        /** @var User $user */
        foreach($users as $user)
        {
            $original = $user->generateNewPassword();

            $user->password = Hash::make($original);

            if($user->save())
            {
                Event::fire('user.password-generated', array($user, $original));
            }
        }

        return json_encode(['status' => 'oke', 'message' => Lang::get('users.emails-sent')]);
    }

}