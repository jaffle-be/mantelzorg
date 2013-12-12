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

    public function subscribe($events)
    {
    }

} 