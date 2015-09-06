<?php

namespace App\Http\Controllers\Instelling;

use App\Organisation\Location;
use App\Organisation\Organisation;
use App\User;
use Auth;
use Hash;
use Input;
use Lang;
use Redirect;
use Session;

class PersonController extends \App\Http\Controllers\AdminController
{

    /**
     * @var \App\User
     */
    protected $user;

    /**
     * @var \App\Organisation\Organisation
     */
    protected $organisation;

    /**
     * @var \App\Organisation\Location
     */
    protected $location;

    public function __construct(Organisation $organisation, User $user, Location $location)
    {
        $this->organisation = $organisation;

        $this->user = $user;

        $this->location = $location;

        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $organisations = $this->organisation->orderBy('name')->get();

        $organisations = array('' => Lang::get('users.pick_organisation')) + $organisations->lists('name', 'id');

        /**
         * We need to load the locations depending on the organisation_id
         * If the user had submit the form and switched organisation, we need to load the locations for that organisation
         */

        if (Input::old() && $user->organisation_id !== Input::old('organisation_id')) {
            $locations = $this->location->where('organisation_id', Input::old('organisation_id'))
                ->orderBy('name')
                ->get()
                ->lists('name', 'id');
        } else if ($user->organisation) {
            $locations = $user->organisation->locations()->orderBy('name')->get()->lists('name', 'id');
        } else {
            $locations = array();
        }

        $locations = array('' => Lang::get('users.pick_location')) + $locations;

        return view('instellingen.index', compact('user', 'organisations', 'locations'));
    }

    public function update()
    {
        $user = Auth::user();

        $validator = $this->user->validator(array('firstname', 'lastname', 'male', 'phone', 'organisation_id', 'organisation_location_id'));

        $validator->sometimes('email', 'required|email|unique:users', function ($input) use ($user) {
            return $user->email !== $input->email;
        });

        /**
         * If the user has entered a new password, the current-password needs to match the existing password
         * The new password needs to be confirmed too! (second rule)
         */
        $validator->sometimes('current-password', 'passcheck', function ($input) {
            return !empty($input->password);
        });

        $validator->sometimes(array('password'), 'required|confirmed', function ($input) {
            return !empty($input->password);
        });

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->messages())->withInput();
        } else {
            $input = Input::all();

            /**
             * make sure to unset the password field, so the password isn't being emptied
             * when the user did not try to change it.
             */
            if (!empty($input['password'])) {
                /**
                 * hash the password to insert it into the database.
                 */
                $input['password'] = Hash::make($input['password']);
                $message = Lang::get('master.info.password_changed');
            } else {
                unset($input['password']);
            }

            $user->update($input);

            return Redirect::route('instellingen.index')->withMessage(isset($message) ? $message : null);
        }
    }
} 