<?php namespace Test\Acceptance;

use App\Mantelzorger\Mantelzorger;
use App\Questionnaire\Panel;
use App\Questionnaire\Questionnaire;
use App\Questionnaire\Session;
use App\User;
use Laracasts\TestDummy\Factory;
use Test\AcceptanceTest;
use WebDriver\Exception\NoSuchElement;

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
        $this->survey = factory('survey')->create(['active' => 1]);

        //panel sort weight needs to be correct this time
        factory('panel')->create(['questionnaire_id' => $this->survey->id, 'panel_weight' => 0]);

        factory('panel')->create(['questionnaire_id' => $this->survey->id, 'panel_weight' => 10]);

        $this->mantelzorger = factory('mantelzorger')->create(['hulpverlener_id' => $this->user->id]);

        $this->oudere = factory('oudere')->create(['mantelzorger_id' => $this->mantelzorger->id]);

        foreach(Panel::all() as $panel)
        {
            $question = factory('mc-question')->create(['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);
            factory('choise', 3)->create(['question_id' => $question->id]);

            $question = factory('mcma-question')->create(['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);
            factory('choise', 3)->create(['question_id' => $question->id]);

            $question = factory('explainable-question')->create(['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);
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
        $session = factory('session')->create([
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
        $this->waitForCss('.instrument-header')->click();
        $this->waitForCss("[data-target-id='$nextPanel->id']")->click();
        $this->updateCurrentUrl();

        $this->waitForCss('.instrument-header')->click();
        $this->waitForCss("[data-target-id='$panel->id']")->click();
        $this->updateCurrentUrl();

        //we're back on the first panel,
        // now lets fill in everything on every panel
        // and navigate only using the bottom navigation
        $this->fillPanel($panel);
        $this->waitForCss('.instrument-footer .btn-instrument')->click();
        $this->updateCurrentUrl();
        $this->fillPanel($nextPanel);
        $this->findCss('.instrument-footer .btn-instrument')->click();
        $this->updateCurrentUrl();

        //we should now have been redirected to the dash, and the session should be finished
        $this->wait(1000)
            ->seePageIs(route('dash'))
            ->assertSame(1, $this->crawler->filter("tr[data-session-id='$session->id'] .fa-check-square-o")->count());
    }

    protected function fillPanel($panel)
    {
        //the first question on the page is open,
        //the others arent -> so we'll first need to click the header.
        foreach($panel->questions as $question)
        {
            if(!$this->isOpen($question))
            {
                $this->toggle($question);
            }

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
                $this->find('choise' . $choise->id)->click();
                $choise = $question->choises->last();
                $this->find('choise' . $choise->id)->click();
            }
            else if($question->explainable)
            {
                //fill 1 explainable question
                $this->typeWrapped($this->wrapper($question), "explanation$question->id", 'some new explanation');
            }

            //summary question can be either explainable and/or (mc or mc-ma)
            //so no need to test it really
        }
    }

    protected function isOpen($question)
    {
        try {
            return $this->waitForCss(sprintf('%s .fa-comment', $this->wrapper($question)))->displayed();
        } catch (NoSuchElement $e) {
            return false;
        }
    }

    protected function toggle($question)
    {
        $wrapper = $this->wrapper($question);
        $this->waitForCss(sprintf('%s .header', $wrapper), 6000)->click();
        $this->waitForCss(sprintf('%s .fa-comment', $wrapper));
    }

    /**
     * @param $question
     *
     * @return string
     */
    protected function wrapper($question)
    {
        return "[data-question-id='$question->id']";
    }

}
