<?php namespace Test\Functional;

use App\Organisation\Organisation;
use App\Questionnaire\Export\Report;
use App\Questionnaire\Jobs\ExportJob;

use App\Questionnaire\Questionnaire;
use Test\AdminFunctionalTest;

class RapportPageTest extends AdminFunctionalTest
{

    protected $organisation;

    protected $survey;

    protected function baseData()
    {
        //we need at least 1 survey, 1 organisation
        $this->organisation = factory(Organisation::class)->create();
        $this->survey = factory(Questionnaire::class)->create();
    }

    public function test_required_fields()
    {
        $this->login();
        $this->visit(route('report.index'));
        $this->submitForm('Aanmaken', []);
        $this->see('vragenlijst is verplicht');
    }

    public function test_generating_all_data_for_a_survey()
    {
        $user = $this->login();

        $this->baseData();

        $this->visit(route('report.index'));

        $this->submitForm('Aanmaken', [
            'survey' => $this->survey->id,
        ]);

        $this->see('alert alert-success');
    }

    public function test_generating_organisation_data()
    {
        $user = $this->login();

        $this->baseData();

        $this->visit(route('report.index'));

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

        $this->visit(route('report.index'));

        $this->submitForm('Aanmaken', [
            'survey' => $this->survey->id,
            'hulpverlener_id' => $user->id,
        ]);

        $this->see('alert alert-success');
    }

    public function test_seeing_existing_reports()
    {
        $user = $this->login();

        $this->baseData();

        $surveys = [];

        array_push($surveys, factory(Report::class)->create());
        array_push($surveys, factory(Report::class, 'user-report')->create());
        array_push($surveys, factory(Report::class, 'organisation-report')->create());

        $this->visit(route('report.index'));

        foreach($surveys as $survey)
        {
            $this->see($survey->filename);
            $this->see($survey->survey_count);
            $this->see($survey->created_at->format('d/m/Y H:i:s'));

            if($survey->user)
            {
                $this->see($survey->user->fullname);
            }

            if($survey->organisation)
            {
                $this->see($survey->organisation->name);
            }
        }

    }

}
