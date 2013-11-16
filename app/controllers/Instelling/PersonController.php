<?php

namespace Instelling;

use Organisation\Organisation;
use Organisation\Location;
use User;
use Auth;
use Input;
use Lang;
use View;
use Redirect;

class PersonController extends \AdminController{

    /**
     * @var \User
     */
    protected $user;

    /**
     * @var \Organisation\Organisation
     */
    protected $organisation;

    /**
     * @var
     */
    protected $location;

    public function __construct(Organisation $organisation, User $user, Location $location)
    {
        $this->organisation = $organisation;

        $this->user = $user;

        $this->location = $location;

        $this->page = 'personal';

        $this->beforeFilter('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $organisations = $this->organisation->orderBy('name')->get();

        $organisations =  array('' => Lang::get('users.pick_organisation')) + $organisations->lists('name', 'id');

        /**
         * We need to load the locations depending on the organisation_id
         * If the user had submit the form and switched organisation, we need to load the locations for that organisation
         */

        if(Input::old() && $user->organisation_id !== Input::old('organisation_id'))
        {
            $locations = $this->location->where('organisation_id', Input::old('organisation_id'))
                ->orderBy('name')
                ->get()
                ->lists('name', 'id');
        }
        else
        {
            $locations = $user->organisation->locations()->orderBy('name')->get()->lists('name', 'id');
        }

        $locations = array('' => Lang::get('users.pick_location')) + $locations;

        $this->layout->content = View::make('instellingen.index', compact(array('user', 'organisations', 'locations')))
            ->nest('subnav', 'layout.admin.subnavs.instellingen', array('page' => $this->page));
    }

    public function update()
    {
        $user = Auth::user();

        $validator = $this->user->validator(array('firstname', 'lastname', 'male', 'phone', 'organisation_id', 'organisation_location_id'));

        $validator->sometimes('email', 'required|email|unique:users', function($input) use ($user){
            return $user->email !== $input->email;
        });

        if($validator->fails())
        {
            return Redirect::back()->withErrors($validator->messages())->withInput();
        }

        else
        {
            $user->update(Input::all());

            return Redirect::route('instellingen.index');
        }
    }
} 