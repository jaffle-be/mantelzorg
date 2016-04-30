<?php

namespace App\Http\Controllers;

use App\Organisation\Location;
use App\Organisation\Organisation;
use App\Search\SearchServiceInterface;
use App\User;
use Event;
use Hash;
use Illuminate\Contracts\Validation\Factory;
use Input;
use Lang;
use Redirect;
use Session;
use Auth;

class HulpverlenerController extends AdminController
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Organisation
     */
    protected $organisation;

    /**
     * @var Location
     */
    protected $location;

    public function __construct(User $user, Organisation $organisation, Location $location)
    {
        $this->user = $user;

        $this->organisation = $organisation;

        $this->location = $location;

        $this->middleware('auth.admin');
    }

    public function index(SearchServiceInterface $search)
    {
        $users = $search->search('users', $this->searchQuery());

        $users->addQuery('query', Input::get('query'));

        return view('hulpverlener.index', compact('users'));
    }

    protected function searchQuery()
    {
        $input = Input::get('query');

        $query = [
            'index' => config('search.index'),
            'type' => 'users',
            'body' => [
                'query' => [
                    'filtered' => [
                        'query' => [
                            'multi_match' => [
                                'fields' => ['firstname', 'lastname', 'email', 'organisation.name'],
                                'query' => $input,
                            ],
                        ],
                        'filter' => [],
                    ],
                ],
                'sort' => [
                    ['created_at' => 'asc'],
                    ['organisation.name' => 'asc'],
                ],
            ],
        ];

        if (empty($input)) {
            $query['body']['query']['filtered']['query'] = ['match_all' => []];
        }

        return $query;
    }

    public function edit($id)
    {
        $user = $this->user->find($id);

        if ($user) {
            $organisations = $this->organisation->get();

            /*
             * create an array that has has en empty first value, then all the organisations, then a 'create new' option
             * organisations have their id as value, new has 'new' as value
             * organisation is mandatory, so no need to add option 'kies organisation'
             */
            $organisations = $organisations->pluck('name', 'id')->all() +
                array('new' => Lang::get('users.new_organisation'));

            /*
             * if the user had switched the organisation, you need to load
             * the locations that are linked to that organisation
             */

            if (Input::old('organisation_id') && $user->organisation_id !== Input::old('organisation_id')) {
                $locations = $this->location->where('organisation_id', Input::old('organisation_id'))
                    ->get()
                    ->pluck('name', 'id')->all();
            } elseif ($user->organisation) {
                $locations = $user->organisation->locations()
                    ->get()
                    ->pluck('name', 'id')->all();
            } else {
                $locations = array();
            }

            /*
             * locations are also mandatory, so we do not add it like we did for inschrijvings form.
             */
            $locations = $locations + array('new' => Lang::get('users.new_location'));

            return view('hulpverlener.edit', compact('user', 'organisations', 'locations', 'inschrijving'));
        } else {
            return Redirect::route('hulpverleners.index');
        }
    }

    public function update($id, Factory $validator)
    {
        $user = $this->user->find($id);

        if (!$user) {
            return Redirect::route('hulpverleners.index');
        }

        $input = Input::all();

        if (!isset($input['active'])) {
            $input['active'] = '0';
        }
        if (!isset($input['admin'])) {
            $input['admin'] = '0';
        }

        $validator = $validator->make(Input::all(), $user->rules(array_keys(Input::all()), ['user' => $user->id]));

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->messages())->withInput($input);
        } else {
            $user->update($input);

            return Redirect::route('hulpverleners.index');
        }
    }

    public function destroy()
    {
        $ids = Input::get('ids');

        if (!empty($ids)) {
            //make sure one cannot delete oneself :-).
            $ids = array_filter($ids, function ($id) {
                return $id != Auth::user()->id;
            });

            if (!empty($ids)) {
                $users = $this->user->whereIn('id', $ids)->with(['mantelzorgers', 'mantelzorgers.oudere'])->get();

                foreach ($users as $user) {
                    $user->delete();
                }
            }
        }

        return [];
    }

    /**
     * Get an array of ids to use to regen the passwords.
     */
    public function regenPasswords()
    {
        $input = Input::get('ids');

        if (!is_array($input) || empty($input)) {
            return json_encode(array(
                'status' => 'no decent input provided.',
            ));
        }

        $users = $this->user->whereIn('id', $input)->get();

        /** @var User $user */
        foreach ($users as $user) {
            $original = $user->generateNewPassword();

            $user->password = Hash::make($original);

            if ($user->save()) {
                Event::fire('user.password-generated', array($user, $original));
            }
        }

        Session::flash('message', Lang::get('users.emails-sent'));

        return json_encode(array('status' => 'oke'));
    }
}
