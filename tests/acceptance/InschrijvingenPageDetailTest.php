<?php namespace Test\Acceptance;

use App\Beta\Registration;
use App\Organisation\Location;
use App\User;
use Laracasts\TestDummy\Factory;
use Test\AdminAcceptanceTest;

class InschrijvingenPageDetailTest extends AdminAcceptanceTest
{

    protected $registration;

    /**
     * @setUp
     * @priority 5
     */
    public function createRegistration()
    {
        $this->registration = factory(Registration::class)->create();
    }

    /**
     * @tearDown
     * @priority 5
     */
    public function deleteRegistrations()
    {
        Registration::whereNotNull('id')->delete();
    }

    protected function start()
    {
        $this->open(route('inschrijvingen.edit', [$this->registration]));

        return $this;
    }

    public function test_required_fields_for_converting_registration_to_user()
    {
        $this->start()
            ->click('create-user')
            ->seePageIs(route('inschrijvingen.edit', [$this->registration]))
            ->see('error-organisation')
            ->see('error-organisation-location');
    }

    public function test_creating_user_with_exisiting_organisation_data()
    {
        $location = factory('location')->create();

        $this->start()
            ->check('male')
            ->select('organisation_id', $location->organisation->id)
            ->wait(1000)
            ->select('organisation_location_id', $location->id)
            ->wait(5000)
            ->find('create-user')->click();

        $this->updateCurrentUrl()
            ->seePageIs(route('inschrijvingen.index'))
            ->notSee('row1');

        $this->open(route('hulpverleners.index'))
            ->see($this->registration->firstname . ' ' . $this->registration->lastname);
    }

    public function test_creating_user_with_new_location()
    {
        $location = factory('location')->create();
        $organisation = $location->organisation;

        $location = Factory::attributesFor('location');
        $payload = array_only($location, ['name', 'street', 'city', 'postal']);

        $this->start()
            ->check('male')
            ->select('organisation_id', $organisation->id)
            ->wait(1000)
            ->select('organisation_location_id', 'new')
            ->submitFormWrapped('locations-creator', 'btn-primary', $payload)
            ->wait(1000)
            ->find('create-user')->click();

        $this->updateCurrentUrl()
            ->seePageIs(route('inschrijvingen.index'))
            ->notSee('row1');

        $user = User::where('email', $this->registration->email)->first();
        $location = Location::where('name', $location['name'])->first();

        $this->assertNotNull($user, 'could not find newly created user');

        $this->open(route('hulpverleners.index'))
            ->click($user->fullname)
            ->seePageIs(route('hulpverleners.edit', [$user]))
            ->isSelected('organisation_location_id', $location->id);
    }

    public function test_creating_with_new_organisation_and_new_location()
    {
        $organisation = Factory::attributesFor('organisation');

        $locationPayload = Factory::attributesFor('location');

        $this->start()
            ->select('organisation_id', 'new')
            ->submitFormWrapped('organisation-creator', 'btn-primary', ['organisation_name' => $organisation['name']])
            ->wait(1000)
            ->submitFormWrapped('locations-creator', 'btn-primary', array_only($locationPayload, ['name', 'street', 'city', 'postal']))
            ->wait(1000)
            ->find('create-user')->click();

        $this->updateCurrentUrl()
            ->seePageIs(route('inschrijvingen.index'));

        $user = User::where('email', $this->registration->email)->first();
        $location = Location::where('name', $locationPayload['name'])->first();

        $this->open(route('hulpverleners.edit', [$user]))
            ->isSelected('organisation_id', $location->organisation->id)
            ->isSelected('organisation_location_id', $location->id);

    }

}
