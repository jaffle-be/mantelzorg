<?php

namespace App\Http\Controllers\Instelling;

use App\Organisation\Location;
use App\Organisation\Organisation;
use App\User;
use Auth;
use Hash;
use Illuminate\Contracts\Validation\Factory;
use Input;
use Lang;
use Redirect;

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

        $organisations = $this->organisation->get();

        $organisations = array('' => Lang::get('users.pick_organisation')) + $organisations->pluck('name', 'id')->all();

        /*
         * We need to load the locations depending on the organisation_id
         * If the user had submit the form and switched organisation, we need to load the locations for that organisation
         */

        if (Input::old() && $user->organisation_id !== Input::old('organisation_id')) {
            $locations = $this->location->where('organisation_id', Input::old('organisation_id'))
                ->get()
                ->pluck('name', 'id')->all();
        } elseif ($user->organisation) {
            $locations = $user->organisation->locations()->get()->pluck('name', 'id')->all();
        } else {
            $locations = array();
        }

        $locations = array('' => Lang::get('users.pick_location')) + $locations;

        return view('instellingen.index', compact('user', 'organisations', 'locations'));
    }

    public function update(Factory $validator)
    {
        $user = Auth::user();

        $validator = $validator->make(Input::all(), $this->user->rules(array_keys(Input::all()), [
            'user' => $user->id,
        ]));

        /*
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

            /*
             * make sure to unset the password field, so the password isn't being emptied
             * when the user did not try to change it.
             */
            if (!empty($input['password'])) {
                /*
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
