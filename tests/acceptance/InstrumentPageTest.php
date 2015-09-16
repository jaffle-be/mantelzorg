<?php namespace Test\Acceptance;

use App\Mantelzorger\Mantelzorger;
use App\Questionnaire\Panel;
use App\Questionnaire\Questionnaire;
use App\Questionnaire\Session;
use App\User;
use Laracasts\TestDummy\Factory;
use Test\AcceptanceTest;

class InstrumentPageTest extends AcceptanceTest
{

    /**
     * @var
     */
    protected $oudere;

    /**
     * @var
     */
    protected $mantelzorger;

    /**
     * @var
     */
    protected $suvey;

    /**
     * @setUp
     * @priority 5
     */
    public function instrument()
    {
        $this->survey = Factory::create('survey', ['active' => 1]);

        //panel sort weight needs to be correct this time
        Factory::create('panel', ['questionnaire_id' => $this->survey->id, 'panel_weight' => 0]);

        Factory::create('panel', ['questionnaire_id' => $this->survey->id, 'panel_weight' => 10]);

        $this->mantelzorger = Factory::create('mantelzorger', ['hulpverlener_id' => $this->user->id]);

        $this->oudere = Factory::create('oudere', ['mantelzorger_id' => $this->mantelzorger->id]);

        foreach(Panel::all() as $panel)
        {
            $question = Factory::create('mc-question', ['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);
            Factory::times(3)->create('choise', ['question_id' => $question->id]);

            $question = Factory::create('mcma-question', ['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);
            Factory::times(3)->create('choise', ['question_id' => $question->id]);

            $question = Factory::create('explainable-question', ['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);
        }
    }

    /**
     * @tearDown
     * @priority 5
     */
    public function cleanInstrument()
    {
        Questionnaire::whereNotNull('id')->delete();
        Mantelzorger::whereNotNull('id')->delete();
        User::whereNotNull('id')->delete();
    }

    public function test_starting_new_session()
    {
        $this->open(route('dash'));

        $this->select('mantelzorger', $this->mantelzorger->id)
            ->wait(1000)
            ->select('oudere', $this->oudere->id)
            ->submitFormWrapped('creator-form', 'btn-primary', [])
            ->wait(1000);

        $panel = $this->survey->panels->first();

        $session = Session::where('mantelzorger_id', $this->mantelzorger->id)
            ->where('user_id', $this->user->id)
            ->where('questionnaire_id', $this->survey->id)
            ->first();

        $this->seePageIs(route('instrument.panel.get', [$panel, $session]));
    }

    public function test_fill_out_instrument()
    {
        $session = Factory::create('session', [
            'mantelzorger_id' => $this->mantelzorger->id,
            'user_id' => $this->user->id,
            'oudere_id' => $this->oudere->id,
            'questionnaire_id' => $this->survey->id,
        ]);

        $this->survey->load(['panels' => function($query)
        {
            $query->orderBy('panel_weight');
        }]);

        $panel = $this->survey->panels->first();
        $nextPanel = $this->survey->nextPanel($panel);

        $route = route('instrument.panel.get', [$panel->id, $session->id]);

        $this->open($route)
            ->seePageIs($route);

        //test top navigation by going through each panel
        $this->find('instrument-header')->click();
        $this->findCss("[data-target-id='$nextPanel->id']")->click();
        $this->updateCurrentUrl();
        $this->find('instrument-header')->click();
        $this->findCss("[data-target-id='$panel->id']")->click();
        $this->updateCurrentUrl();


        //we're back on the first panel,
        // now lets fill in everything on every panel
        // and navigate only using the bottom navigation
        $this->snap();
        $this->fillPanel($panel);
        $this->findCss('.instrument-footer .btn-instrument')->click();
        $this->updateCurrentUrl();
        $this->fillPanel($nextPanel);
        $this->findCss('.instrument-footer .btn-instrument')->click();
        $this->updateCurrentUrl();

        //we should now have been redirected to the dash, and the session should be finished
        $this->wait(1)
            ->seePageIs(route('dash'))
            ->assertSame(1, $this->crawler->filter("tr[data-session-id='$session->id'] .fa-check-square-o")->count());
    }

    protected function fillPanel($panel)
    {
        foreach($panel->questions as $question)
        {

            if($question->multiple_choise && !$question->multiple_answer)
            {
                //fill 1 mc question
                $choise = $question->choises->first();
                $this->findCss(sprintf("[data-question-id='%d'] [value='%d']", $question->id, $choise->id))->click();
            }
            else if($question->multiple_choise && $question->multiple_answer)
            {
                //fill 1 mc-ma question
                $choise = $question->choises->first();
                $this->findCss(sprintf("[data-question-id='%d'] [value='%d']", $question->id, $choise->id))->click();
                $choise = $question->choises->last();
                $this->findCss(sprintf("[data-question-id='%d'] [value='%d']", $question->id, $choise->id))->click();
            }
            else if($question->explainable)
            {
                //fill 1 explainable question
                $this->typeWrapped("[data-question-id='$question->id']", "explanation$question->id", 'some new explanation');
            }

            //summary question can be either explainable and/or (mc or mc-ma)
            //so no need to test it really
        }
    }


}