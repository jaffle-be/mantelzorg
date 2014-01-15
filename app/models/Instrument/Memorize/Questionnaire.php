<?php

namespace Instrument\Memorize;

use Mantelzorger\Mantelzorger;
use Mantelzorger\Oudere;
use Questionnaire\Panel;
use Questionnaire\Question;
use Questionnaire\Answer;
use Input;
use Questionnaire\Session;

class Questionnaire {

    protected $answer;

    protected $question;

    protected $mantelzorger;

    protected $oudere;

    public function __construct(Answer $answer, Question $question, Mantelzorger $mantelzorger, Oudere $oudere, Session $survey)
    {
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

    public function question(Question $question, Session $session)
    {
        if($question->explainable == 1)
        {
            $explanation = $this->explanation($question);
        }

        if($question->multiple_choise == 1)
        {
            $choises = $this->choise($question);
        }

        $this->persist($question, $session, isset($explanation) ? $explanation : null, isset($choises) ? $choises : null);
    }

    protected function explanation($question)
    {
        return Input::get('explanation' . $question->id);
    }

    protected function choise($question)
    {
        return Input::get('question' . $question->id);
    }

    protected function persist($question, $session, $explanation = null, $choises = null)
    {
        //does this need a check? so it wouldn't be loaded all the time?
        $session->load(array('answers'));

        $answer = $session->answers->filter(function($item) use ($question)
        {
            if($item->question_id === $question->id)
            {
                return true;
            }
        })->first();

        if(!$answer)
        {
            $answer = $this->answer->create(array(
                'session_id' => $session->id,
                'question_id' => $question->id,
                'explanation' => $explanation,
            ));
        }

        $answer->load('choises');

        if(is_array($choises))
        {
            $answer->choises()->sync($choises);

            $answer->touch();
        }
        else if($choises)
        {
            $answer->choises()->sync(array($choises));

            $answer->touch();
        }
    }

} 