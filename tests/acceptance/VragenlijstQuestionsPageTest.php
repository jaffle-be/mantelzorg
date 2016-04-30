<?php namespace Test\Acceptance;

use App\Questionnaire\Choise;
use App\Questionnaire\Panel;
use App\Questionnaire\Questionnaire;
use Laracasts\TestDummy\Factory;
use Test\AdminAcceptanceTest;

class VragenlijstQuestionsPageTest extends AdminAcceptanceTest
{

    /**
     * @setup
     * @priority 5
     */
    public function survey()
    {
        $survey = factory('survey')->create();

        $panel = Factory::create('panel', ['questionnaire_id' => $survey->id]);
    }

    /**
     * @tearDown
     * @priority 5
     */
    public function cleanSurvey()
    {
        Questionnaire::whereNotNull('id')->delete();
    }

    public function test_creating_question()
    {
        $panel = Panel::first();

        $this->open(route('panel.{panel}.question.index', [$panel]));

        $this->find('question-creator-trigger')->click();

        $this->submitFormWrapped('question-creator', 'btn-primary', []);

        $this->wait(1000)
            ->see('titel is verplicht')
            ->see('vraagstelling is verplicht');


        $question = Factory::attributesFor('question');

        $payload = array_only($question, ['title', 'question']);

        $this->submitFormWrapped('question-creator', 'btn-primary', $payload)
            ->wait(1000)
            ->seeInDatabase('questionnaire_questions', array_merge($payload, [
                'questionnaire_id' => $panel->questionnaire_id,
                'questionnaire_panel_id' => $panel->id
            ]));

        $question = $panel->questions->first();

        //we'll also quickly test if we can change this record.
        $wrapper = "[data-question-id='$question->id']";
        $this->typeWrapped($wrapper, "title$question->id", "some new title");
        $this->typeWrapped($wrapper, "question$question->id", "some new question");
        $this->typeWrapped($wrapper, "meta$question->id", "some new meta");
        $this->findWrapped($wrapper, ".summary_question_control")->click();
        $this->findWrapped($wrapper, ".explainable_control")->click();
        $this->findWrapped($wrapper, ".multiple_choise_control")->click();
        $this->wait(1000);
        $this->findWrapped($wrapper, ".multiple_answer_control")->click();

        //unfocus to trigger save
        $this->click('page-header');
        $this->wait(1500)
            ->seeInDatabase('questionnaire_questions', [
                'id' => $question->id,
                'questionnaire_id' => $panel->questionnaire_id,
                'questionnaire_panel_id' => $panel->id,
                'title' => 'some new title',
                'question' => 'some new question',
                'meta' => 'some new meta',
                'summary_question' => 1,
                'explainable' => 1,
                'multiple_choise' => 1,
                'multiple_answer' => 1,
            ]);
    }

    public function test_cannot_create_choise_when_not_multiple_choise()
    {
        $panel = Panel::first();
        //lets add a none multiple choise
        $question = Factory::create('question', [
            'questionnaire_id' => $panel->questionnaire_id,
            'questionnaire_panel_id' => $panel->id,
        ]);

        $this->open(route('panel.{panel}.question.index', [$panel]));

        $this->assertFalse($this->findWrapped("[data-question-id='$question->id']", "add-choise")->displayed());
    }

    public function test_required_fields_creating_choise()
    {
        $panel = Panel::first();

        $question = Factory::create('mc-question', [
            'questionnaire_id' => $panel->questionnaire_id,
            'questionnaire_panel_id' => $panel->id,
        ]);

        $this->open(route('panel.{panel}.question.index', [$panel]));

        $this->findWrapped("[data-question-id='$question->id']", "add-choise")->click();

        $this->submitFormWrapped('choise-creator', 'btn-primary', [])
            ->wait(1000)
            ->see('titel is verplicht');

        $this->submitFormWrapped('choise-creator', 'btn-primary', [
            'title' => 'some title'
        ])
            ->wait(1000)
            ->seeInDatabase('questionnaire_choises', [
                'question_id' => $question->id,
                'title' => 'some title',
            ]);
    }

    public function test_changing_labels()
    {
        $panel = Panel::first();

        $question = Factory::create('mc-question', ['questionnaire_id' => $panel->questionnaire_id, 'questionnaire_panel_id' => $panel->id]);

        Factory::times(4)->create('choise', ['question_id' => $question->id]);

        $choise = Choise::first();

        $this->open(route('panel.{panel}.question.index', [$panel]));

        $this->typeWrapped("[data-choise-id='$choise->id']", ".name", 'new label value')
            ->click('page-header');

        $this->wait(1000);

        $this->seeInDatabase('questionnaire_choises', [
            'id'          => $choise->id,
            'question_id' => $question->id,
            'title'       => 'new label value'
        ]);

        $src = $this->findCss("[data-choise-id='$choise->id'] .handle");
        $target = $this->findCss("[data-choise-id]:last-child");

        $this->dragAndDrop($src, $target);

        $this->wait(1000);

        $this->seeInDatabase('questionnaire_choises', [
            'id'          => $choise->id,
            'question_id' => $question->id,
            'title'       => 'new label value',
            'sort_weight' => (Choise::count() -1) * 10
        ]);
    }

}
