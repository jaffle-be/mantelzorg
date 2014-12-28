<?php
use Organisation\Organisation;
use Beta\Registration;

class InschrijvingController extends AdminController
{

    /**
     * @var Beta\Registration
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

        $this->beforeFilter('auth.admin');
    }

    public function index()
    {
        $query = Input::get('query');

        $search = $this->registration->search();

        $registrations = $search->whereMulti_match(['email', 'firstname', 'lastname'], $query)
            ->orderBy('created_at', 'asc')
            ->orderBy('firstname', 'asc')
            ->orderBy('lastname', 'asc')
            ->get();

        $registrations->appends('query', $query);

        $this->layout->content = View::make('inschrijving.index', compact(array('registrations')));
    }

    public function edit($id)
    {
        $inschrijving = $this->registration->find($id);

        if ($inschrijving) {
            $organisations = $this->organisation->orderBy('name')->get();

            //create an array that has has en empty first value, then all the organisations, then a 'create new' option
            //empty has no value, organisations have their id as value, new has 'new' as value
            $organisations = array('' => Lang::get('users.pick_organisation')) +
                $organisations->lists('name', 'id') +
                array('new' => Lang::get('users.new_organisation'));

            if (Input::old('organisation_id')) {
                $organisation = $this->organisation->with(array('locations'))->find(Input::old('organisation_id'));

                $locations = $organisation->locations->lists('name', 'id');
            } else {
                $locations = array();
            }

            $locations = array('' => Lang::get('users.pick_location'))
                + $locations
                + array('new' => Lang::get('users.new_location'));

            $this->layout->content = View::make('inschrijving.edit', compact('inschrijving', 'organisations', 'locations'))
                ->nest('creatorOrganisations', 'modals.organisation-creator', compact(array('inschrijving')))
                ->nest('creatorLocations', 'modals.location-creator', compact(array('inschrijving')));
        } else {
            return Redirect::back();
        }
    }

    public function update()
    {
        $paswoord = $this->user->generateNewPassword();

        $input = array_merge(Input::all(), array('password' => $paswoord));

        $validator = $this->user->validator(null, $input);

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

            return Redirect::action('InschrijvingController@index');
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