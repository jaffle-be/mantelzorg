<?php namespace Test\Functional;

use App\Questionnaire\Panel;
use App\Questionnaire\Questionnaire;
use Laracasts\TestDummy\Factory;
use Test\AdminFunctionalTest;

class VragenlijstPageTest extends AdminFunctionalTest
{

    public function test_basic_page_info()
    {
        $user = $this->login();

        $this->surveys();

        $questionnaires = Questionnaire::all();

        $this->visit(route('survey.index'));

        foreach($questionnaires as $questionnaire)
        {
            $this->see($questionnaire->title);
            if($questionnaire->active)
            {
                $this->assertSame(1, $this->crawler->filter(sprintf('[data-questionnaire-id="%d"] .fa-check-square-o', $questionnaire->id))->count());
            }
            else{
                $this->assertSame(1, $this->crawler->filter(sprintf('[data-questionnaire-id="%d"] .fa-square-o', $questionnaire->id))->count());
            }

            $this->panel_info($questionnaire);
        }
    }

    protected function surveys()
    {
        Factory::times(2)->create('survey');
        Factory::create('survey', ['active' => 1]);

        foreach(Questionnaire::all() as $questionnaire)
        {
            Factory::create('panel', ['questionnaire_id' => $questionnaire->id]);
        }
    }

    protected function panel_info($questionnaire)
    {
        foreach($questionnaire->panels as $panel)
        {
            $selector = sprintf('[data-questionnaire-panel-id="%d"] i.panel-%s', $panel->id, $panel->color);

            $this->assertSame(1, $this->crawler->filter($selector)->count());

            $selector = sprintf('[data-questionnaire-panel-id="%d"] [data-panel-weight="%d"]', $panel->id, $panel->panel_weight);

            $this->assertSame(1, $this->crawler->filter($selector)->count());
        }
    }

    public function test_navigation_to_panel()
    {
        $this->login();

        $this->surveys();

        $panel = Panel::first();

        $link = $this->visit(route('survey.index'))
            ->crawler->filter("[data-questionnaire-panel-id=$panel->id] a.btn-default");

        $this->assertSame(1, $link->count());
        $this->visit($link->link()->getUri())
            ->seePageIs(route('panel.{panel}.question.index', [$panel]));
    }

}