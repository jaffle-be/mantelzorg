<?php

namespace Questionnaire;

class EventSubscriber{

    /**
     * @var Questionnaire
     */
    protected $questionnaire;

    /**
     * @var Panel
     */
    protected $panel;

    /**
     * @var Question
     */
    protected $question;

    /**
     * @var Choise
     */
    protected $choise;

    /**
     * @var Answer
     */
    protected $answer;

    public function __construct(Questionnaire $questionnaire, Panel $panel, Question $question, Choise $choise, Answer $answer)
    {
        $this->questionnaire = $questionnaire;
        $this->panel = $panel;
        $this->question = $question;
        $this->choise = $choise;
        $this->answer =$answer;
    }

    /**
     * Allow only 1 active questionnaire at a time.
     */
    public function activation()
    {
        $questionnaires = $this->questionnaire->where('active', '1')->get();

        foreach($questionnaires as $questionnaire)
        {
            $questionnaire->active = 0;

            $questionnaire->save();
        }
    }

    public function subscribe($events)
    {
        $events->listen('questionnaire.activation', 'Questionnaire\EventSubscriber@activation');
    }

} 