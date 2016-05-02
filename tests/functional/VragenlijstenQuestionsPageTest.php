<?php namespace Test\Functional;

use App\Questionnaire\Choise;

use App\Questionnaire\Panel;
use App\Questionnaire\Question;
use App\Questionnaire\Questionnaire;
use Test\AdminFunctionalTest;

class VragenlijstenQuestionsPageTest extends AdminFunctionalTest
{

    protected function panel($questions = true)
    {
        $survey = factory(Questionnaire::class)->create();

        $panel = factory(Panel::class)->make();

        $survey->panels()->save($panel);

        if($questions)
        {
            factory(Question::class, 3)->create([
                'questionnaire_id' => $survey->id,
                'questionnaire_panel_id' => $panel->id,
            ]);
        }

        return $panel;
    }

    public function test_seeing_existing_questions()
    {
        $user = $this->login();

        $panel = $this->panel();

        $this->visit(route('panel.{panel}.question.index', [$panel]));

        //we should see all 3 divs
        $this->assertSame(3, $this->crawler->filter('[data-question-id]')->count());
    }

    public function test_seeing_their_options()
    {
        $this->login();

        $panel = $this->panel();

        $question = factory(Question::class)->create(['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);
        $mcquestion = factory(Question::class, 'mc-question')->create(['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);
        //we use mc question to test showing choises.

        factory(Choise::class, 4)->create(['question_id' => $mcquestion->id]);

        $mcmaquestion = factory(Question::class, 'mcma-question')->create(['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);
        $explainable = factory(Question::class, 'explainable-question')->create(['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);
        $summary = factory(Question::class, 'summary-question')->create(['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);

        $this->visit(route('panel.{panel}.question.index', [$panel]));

        //question
        $this->see($question->title)
            ->see($question->question)
            ->see($question->meta);

        $this->assertSame(0, $this->crawler->filter("[data-question-id='$question->id'] .multiple_choise:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$question->id'] .multiple_answer:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$question->id'] .explainable:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$question->id'] .summary_question:checked")->count());

        //mc tests
        $this->see($mcquestion->title)
            ->see($mcquestion->question)
            ->see($mcquestion->meta);

        $this->assertSame(1, $this->crawler->filter("[data-question-id='$mcquestion->id'] .multiple_choise:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$mcquestion->id'] .multiple_answer:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$mcquestion->id'] .explainable:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$mcquestion->id'] .summary_question:checked")->count());

        //see all choises within container for mc question (we created 4)
        $this->assertSame(4, $this->crawler->filter("[data-question-id='$mcquestion->id'] [data-choise-id]")->count());

        //mcma
        $this->see($mcmaquestion->title)
            ->see($mcmaquestion->question)
            ->see($mcmaquestion->meta);

        $this->assertSame(1, $this->crawler->filter("[data-question-id='$mcmaquestion->id'] .multiple_choise:checked")->count());
        $this->assertSame(1, $this->crawler->filter("[data-question-id='$mcmaquestion->id'] .multiple_answer:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$mcmaquestion->id'] .explainable:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$mcmaquestion->id'] .summary_question:checked")->count());

        //explainable
        $this->see($explainable->title)
            ->see($explainable->question)
            ->see($explainable->meta);

        $this->assertSame(0, $this->crawler->filter("[data-question-id='$explainable->id'] .multiple_choise:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$explainable->id'] .multiple_answer:checked")->count());
        $this->assertSame(1, $this->crawler->filter("[data-question-id='$explainable->id'] .explainable:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$explainable->id'] .summary_question:checked")->count());


        //summary
        $this->see($summary->title)
            ->see($summary->question)
            ->see($summary->meta);

        $this->assertSame(1, $this->crawler->filter("[data-question-id='$summary->id'] .multiple_choise:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$summary->id'] .multiple_answer:checked")->count());
        $this->assertSame(0, $this->crawler->filter("[data-question-id='$summary->id'] .explainable:checked")->count());
        $this->assertSame(1, $this->crawler->filter("[data-question-id='$summary->id'] .summary_question:checked")->count());
    }

}
