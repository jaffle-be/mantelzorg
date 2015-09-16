<?php namespace Test\Functional;

use App\Questionnaire\Jobs\ExportJob;
use Laracasts\TestDummy\Factory;
use Test\AdminFunctionalTest;

class RapportPageTest extends AdminFunctionalTest
{

    protected $organisation;

    protected $survey;

    protected function baseData()
    {
        //we need at least 1 survey, 1 organisation
        $this->organisation = Factory::create('organisation');
        $this->survey = Factory::create('survey');
    }

    public function test_required_fields()
    {
        $this->login();
        $this->visit(route('rapport.index'));
        $this->submitForm('Aanmaken', []);
        $this->see('vragenlijst is verplicht');
    }

    public function test_generating_all_data_for_a_survey()
    {
        $user = $this->login();

        $this->baseData();

        $this->visit(route('rapport.index'));

        $this->submitForm('Aanmaken', [
            'survey' => $this->survey->id,
        ]);

        $this->see('alert alert-success');
    }

    public function test_generating_organisation_data()
    {
        $user = $this->login();

        $this->baseData();

        $this->visit(route('rapport.index'));

        $this->submitForm('Aanmaken', [
            'survey' => $this->survey->id,
            'organisation_id' => $this->organisation->id,
        ]);

        $this->see('alert alert-success');
    }

    public function test_generating_hulpverlener_data()
    {
        $user = $this->login();

        $this->baseData();

        $this->visit(route('rapport.index'));

        $this->submitForm('Aanmaken', [
            'survey' => $this->survey->id,
            'hulpverlener_id' => $user->id,
        ]);

        $this->see('alert alert-success');
    }

}