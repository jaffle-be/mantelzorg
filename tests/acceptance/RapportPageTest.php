<?php namespace Test\Acceptance;

use App\Organisation\Organisation;
use App\Questionnaire\Export\Report;
use App\Questionnaire\Questionnaire;
use Laracasts\TestDummy\Factory;
use Test\AdminAcceptanceTest;

class RapportPageTest extends AdminAcceptanceTest
{

    protected $survey;

    protected $organisation;

    /**
     * @setUp
     * @priority 5
     */
    public function baseData()
    {
        //we need at least 1 survey, 1 organisation
        $this->organisation = factory('organisation')->create();
        $this->survey = factory('survey')->create();

        factory('report', 5)->create(['questionnaire_id' => $this->survey->id]);
    }

    /**
     * @tearDown
     * @priority 5
     */
    public function cleanBaseData()
    {
        Organisation::whereNotNull('id')->delete();
        Questionnaire::whereNotNull('id')->delete();
    }

    public function test_disabling_fields_when_selecting_filters()
    {
        $this->open(route('report.index'));
        $this->assertSame(null, $this->find('hulpverlener_id')->attribute('disabled'));

        //selecting instrument will not trigger a disabled
        $this->select('survey', $this->survey->id);
        $this->assertSame(null, $this->find('hulpverlener_id')->attribute('disabled'));

        //selecting an organisation will
        $this->select('organisation_id', $this->organisation->id);
        $this->assertSame('true', $this->find('hulpverlener_id')->attribute('disabled'));

        //deselecting the organisation will make it enabled again
        $this->select('organisation_id', '');
        $this->assertSame(null, $this->find('hulpverlener_id')->attribute('disabled'));

        //selecting the user will make organisation disabled
        $this->select('hulpverlener_id', $this->user->id);
        $this->assertSame('true', $this->find('organisation_id')->attribute('disabled'));

        //deselecting it will make it enabled again
        $this->select('hulpverlener_id', '');
        $this->assertSame(null, $this->find('organisation_id')->attribute('disabled'));
    }

    public function test_deleting_reports()
    {
        $this->open(route('report.index'));

        $this->click('actions');
        $this->click('select-all');
        $this->click('actions');
        $this->click('remove');
        $this->click('confirm');
        $this->wait(1000);
        $this->seePageIs(route('report.index'))
            ->notSee('<td>');

        $this->assertCount(0, Report::all());
    }

}
