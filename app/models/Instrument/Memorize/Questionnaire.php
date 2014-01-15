<?php

namespace Instrument\Memorize;

use Illuminate\Session\Store;
use Mantelzorger\Mantelzorger;
use Mantelzorger\Oudere;
use Questionnaire\Panel;
use Questionnaire\Question;
use Questionnaire\Answer;
use Input;
use Questionnaire\Session;

class Questionnaire {

    protected $session;

    protected $answer;

    protected $question;

    protected $mantelzorger;

    protected $oudere;

    public function __construct(Store $session, Answer $answer, Question $question, Mantelzorger $mantelzorger, Oudere $oudere, Session $survey)
    {
        $this->session = $session;

        $this->answer = $answer;

        $this->question = $question;

        $this->mantelzorger = $mantelzorger;

        $this->oudere = $oudere;

        $this->survey = $survey;
    }

    public function newSurvey($mantelzorger, $oudere)
    {
        return $this->survey->create(array(
            'mantelzorger_id' => $mantelzorger->id,
            'oudere_id' => $oudere->id
        ));
    }

    public function question(Question $question)
    {
        $answers = $this->answers();

        if(!isset($answers[$question->id]))
        {
            $answers[$question->id] = array();
        }

        if($question->explainable == 1)
        {
            $answers[$question->id]['explanation'] = $this->explanation($question);
        }

        if($question->multiple_choise == 1)
        {
            $answers[$question->id]['choises'] = $this->choise($question);
        }

        $this->update($answers);

    }

    protected function explanation($question)
    {
        return Input::get('explanation' . $question->id);
    }

    protected function choise($question)
    {
        return Input::get('question' . $question->id);
    }

} 