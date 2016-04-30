<?php namespace Test\Acceptance;

use App\Organisation\Location;
use App\Organisation\Organisation;
use Laracasts\TestDummy\Factory;
use Test\AdminAcceptanceTest;

class HulpverlenerDetailPageTest extends AdminAcceptanceTest
{

    protected $user;

    /**
     * @setUp
     * @priority 5
     */
    public function editableUser()
    {
        $this->user = factory('user')->create();
    }

    protected function start()
    {
        $this->open(route('hulpverleners.edit', [$this->user]))
            ->seePageIs(route('hulpverleners.edit', [$this->user]));

        return $this;
    }

    public function test_required_fields_for_new_location()
    {
        $this->start()
            ->select('organisation_location_id', 'new')
            ->click('create-location')
            ->see('error-name')
            ->see('error-street')
            ->see('error-postal')
            ->see('error-city');
    }

    public function test_creating_location()
    {
        $location = app(Factory::class)->raw('location', ['name' => 'knownLocationName']);

        $location = array_only($location, ['name', 'street', 'postal', 'city']);

        $this->start()
            ->select('organisation_location_id', 'new')
            ->submitFormWrapped('locations-creator', 'btn-primary', $location)
            ->submitForm('Gegevens bewaren');

        $location = Location::whereName('knownLocationName')->first();

        $this->start()
            ->isSelected('organisation_id', $this->user->organisation_id)
            ->isSelected('organisation_location_id', $location->id);
    }

    public function test_creating_organisation()
    {
        $this->start();

        $organisation = ['organisation_name' => 'knownOrganisationName'];
        $location = array_only(app(Factory::class)->raw('location', ['name' => 'knownLocationName']), ['name', 'street', 'postal', 'city']);

        $this->select('organisation_id', 'new')
            ->submitFormWrapped('organisation-creator', 'btn-primary', $organisation)
            ->wait(1000)
            ->see('locations-creator')
            ->submitFormWrapped('locations-creator', 'btn-primary', $location)
            ->wait(1000)
            ->submitForm('Gegevens bewaren')

            ->see('knownOrganisationName');

        $location = Location::whereName('knownLocationName')->first();
        $organisation = Organisation::whereName('knownOrganisationName')->first();

        $this->start()
            ->isSelected('organisation_id', $organisation->id)
            ->isSelected('organisation_location_id', $location->id);
    }

}
