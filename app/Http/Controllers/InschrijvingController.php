<?php

namespace App\Http\Controllers;

use App\Beta\Registration;
use App\Organisation\Organisation;
use App\Search\SearchServiceInterface;
use App\User;
use Event;
use Hash;
use Illuminate\Contracts\Validation\Factory;
use Input;
use Lang;
use Redirect;

class InschrijvingController extends AdminController
{
    /**
     * @var \App\Beta\Registration
     */
    protected $registration;

    /**
     * @var Organisation
     */
    protected $organisation;

    /**
     * @var User;
     */
    protected $user;

    public function __construct(Registration $registration, Organisation $organisation, User $user)
    {
        $this->registration = $registration;

        $this->organisation = $organisation;

        $this->user = $user;

        $this->middleware('auth.admin');
    }

    public function index(SearchServiceInterface $search)
    {
        $query = Input::get('query');

        $count = $this->registration->count();

        if ($count == 0) {
            //put count to 1 to force a pagination result, instead of a regular collection.
            $count = 1;
        }

        $registrations = $search->search('beta_registrations', $this->searchQuery(), [], $count);

        $registrations->addQuery('query', $query);

        return view('inschrijving.index', compact('registrations'));
    }

    protected function searchQuery()
    {
        $input = Input::get('query');

        $query = [
            'index' => config('search.index'),
            'type' => 'beta_registrations',
            'body' => [
                'query' => [
                    'filtered' => [
                        'query' => [
                            'multi_match' => [
                                'fields' => ['email', 'firstname', 'lastname'],
                                'query' => $input,
                            ],
                        ],
                        'filter' => [

                        ],
                    ],
                ],
                'sort' => [
                    ['created_at' => 'asc'],
                    ['firstname' => 'asc'],
                    ['lastname' => 'asc'],
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
        $inschrijving = $this->registration->find($id);

        if ($inschrijving) {
            $organisations = $this->organisation->get();

            //create an array that has has en empty first value, then all the organisations, then a 'create new' option
            //empty has no value, organisations have their id as value, new has 'new' as value
            $organisations = array('' => Lang::get('users.pick_organisation')) +
                $organisations->pluck('name', 'id')->all() +
                array('new' => Lang::get('users.new_organisation'));

            if (Input::old('organisation_id')) {
                $organisation = $this->organisation->with(array('locations'))->find(Input::old('organisation_id'));

                $locations = $organisation->locations->pluck('name', 'id')->all();
            } else {
                $locations = array();
            }

            $locations = array('' => Lang::get('users.pick_location'))
                + $locations
                + array('new' => Lang::get('users.new_location'));

            return view('inschrijving.edit', compact('inschrijving', 'organisations', 'locations', 'inschrijving'));
        } else {
            return Redirect::back();
        }
    }

    public function update(Factory $validator)
    {
        $paswoord = $this->user->generateNewPassword();

        $input = array_merge(Input::all(), array('password' => $paswoord));

        $validator = $validator->make($input, $this->user->rules(array_keys($input), [
            'user' => $input['id'],
        ]));

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->messages())->withInput();
        } else {
            $inschrijving = $this->registration->find(Input::get('id'));

            //hash password before inserting
            $original = $input['password'];
            $input['password'] = Hash::make($input['password']);
            //set user active
            $input['active'] = '1';

            $user = $this->user->create($input);

            if ($user) {
                Event::fire('user.password-generated', array($user, $original));
            }

            $inschrijving->delete();

            return Redirect::route('inschrijvingen.index');
        }
    }

    public function destroy()
    {
        $ids = Input::get('ids');

        if (count($ids)) {
            $registrations = $this->registration->whereIn('id', $ids)->get();

            foreach ($registrations as $registration) {
                $registration->delete();
            }
        }

        return [];
    }
}
