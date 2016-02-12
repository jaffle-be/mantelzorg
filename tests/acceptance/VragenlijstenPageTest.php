<?php namespace Test\Acceptance;

use App\Questionnaire\Questionnaire;
use Laracasts\TestDummy\Factory;
use Test\AdminAcceptanceTest;

class VragenlijstenPageTest extends AdminAcceptanceTest
{

    /**
     * @setUp
     * @priority 5
     */
    public function surveys()
    {
        Factory::create('survey', ['active' => 1]);
        Factory::times(2)->create('survey');

        foreach (Questionnaire::all() as $survey) {
            Factory::times(4)->create('panel', ['questionnaire_id' => $survey->id]);
        }
    }

    /**
     * @tearDown
     * @priority 5
     */
    public function cleanSurveys()
    {
        Questionnaire::whereNotNull('id')->delete();
    }

    public function test_activating()
    {
        $this->open(route('survey.index'));

        $questionnaire = Questionnaire::where('active', 0)->first();

        //first survey is active, so we click the activate for questionnaire 2
        $this->findCss(sprintf('[data-questionnaire-id="%d"] [data-trigger="activate"]', $questionnaire->id))->click();

        $this->wait(1000);

        //this test doesnt have any assertions, the assertion is that the following element is found, if not, it will throw an error anyway
        $this->open(route('survey.index'))
            ->findCss(sprintf('[data-questionnaire-id="%d"] [data-trigger="deactivate"]', $questionnaire->id));
    }

    public function test_changing_inputs()
    {
        $this->open(route('survey.index'));

        $questionnaire = Questionnaire::first();
        $panel = $questionnaire->panels->first();

        $panelwrapper = $this->panel($panel);

        $this->typeWrapped("[data-questionnaire-id='$questionnaire->id']", 'title', 'Some new title');
        $this->typeWrapped($panelwrapper, "title", 'Some new panel title');

        //unfocus to trigger save
        $this->find('page-header')->click();
        $this->wait(1000);

        foreach($questionnaire->panels as $panel)
        {
            $panelwrapper = $this->panel($panel);
            $this->findWrapped($panelwrapper, "dropdown-toggle")->click();
            $this->findWrapped($panelwrapper, "panel-green")->click();
        }

        //swap to panels
        $first = $questionnaire->panels->first();
        $last = $questionnaire->panels->last();

        $this->dragAndDrop($this->findWrapped($this->panel($first), "ui-sortable-handle"), $this->findWrapped($this->panel($last),  "ui-sortable-handle"));

        $this->wait(1000)->open(route('survey.index'))->snap()
            ->see('Some new title')
            ->see('Some new panel title');

        foreach($questionnaire->panels as $panel)
        {
            $this->findWrapped($this->panel($panel), 'i.panel-green');
        }

        //$first should get weight from last and vice versa
        $this->seeInDatabase('questionnaire_panels', [
            'id' => $first->id,
            'panel_weight'=> ($questionnaire->panels->count() - 1) * 10,
        ]);

        $this->seeInDatabase('questionnaire_panels', [
            'id' => $last->id,
            'panel_weight'=> ($questionnaire->panels->count() -2) * 10,
        ]);


    }

    public function test_creating_survey()
    {
        $this->open(route('survey.index'))
            ->find('.btn-primary.btn-fab')->click();

        $this->notSee('titel is verplicht');
        $this->submitFormWrapped('questionnaire-creator', 'btn-primary', []);
        $this->wait(1000)
            ->see('titel is verplicht')
            ->submitFormWrapped('questionnaire-creator', 'btn-primary', ['title' => 'Some title'])
            ->wait(1000)
            ->seePageIs(route('survey.index'))
            ->open(route('survey.index'))
            ->find('.btn-primary.btn-fab')->click();

        $this->submitFormWrapped('questionnaire-creator', 'btn-primary', ['title' => 'Some title'])
            ->see('titel is al in gebruik');
    }

    public function test_creating_panel()
    {
        $toggle = '[data-toggle="panel-creator"]';

        $this->open(route('survey.index'))
            ->find($toggle)->click();

        $this->submitFormWrapped('panel-creator', 'btn-primary', [])
            ->wait(1000)
            ->see('titel is verplicht')
            ->submitFormWrapped('panel-creator', 'btn-primary', [
                'title' => 'Some title',
            ]);

        $this->wait(1000)
            ->seePageIs(route('survey.index'))
            ->see('Some title');

        $this->open(route('survey.index'));

        $this->find($toggle)->click();

        $this->submitFormWrapped('panel-creator', 'btn-primary', [
            'title' => 'Some title'
        ])
            ->wait(1000)
            ->see('titel is al in gebruik');
    }

    /**
     * @param $panel
     *
     * @return string
     */
    protected function panel($panel)
    {
        $panelwrapper = "[data-questionnaire-panel-id='$panel->id']";

        return $panelwrapper;
    }

}