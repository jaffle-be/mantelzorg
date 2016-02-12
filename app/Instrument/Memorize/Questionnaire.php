<?php

namespace App\Instrument\Memorize;

use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;
use App\Questionnaire\Answer;
use App\Questionnaire\Question;
use App\Questionnaire\Session;
use Auth;
use Input;

class Questionnaire
{
    protected $auth;

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

    public function newSurvey($mantelzorger, $oudere, $questionnaire)
    {
        return $this->survey->create(array(
            'user_id' => Auth::user()->id,
            'mantelzorger_id' => $mantelzorger->id,
            'oudere_id' => $oudere->id,
            'questionnaire_id' => $questionnaire->id,
        ));
    }

    public function question(Question $question, Session $session)
    {
        if ($question->explainable == 1) {
            $explanation = $this->explanation($question);
        }

        if ($question->multiple_choise == 1) {
            $choises = $this->choise($question);
        }

        $this->persist($question, $session, isset($explanation) ? $explanation : null, isset($choises) ? $choises : null);
    }

    protected function explanation($question)
    {
        return Input::get('explanation'.$question->id);
    }

    protected function choise($question)
    {
        return Input::get('question'.$question->id);
    }

    protected function persist($question, $session, $explanation = null, $choises = null)
    {
        //does this need a check? so it wouldn't be loaded all the time?
        $session->load(array('answers'));

        $answer = $session->answers->filter(function ($item) use ($question) {
            if ($item->question_id == $question->id) {
                return true;
            }
        })->first();

        if (!$answer) {
            $answer = $this->answer->create(array(
                'session_id' => $session->id,
                'question_id' => $question->id,
                'explanation' => $explanation,
            ));
        } else {
            $answer->explanation = $explanation;
            $answer->update();
        }

        $answer->load('choises');

        if (is_array($choises)) {
            $answer->choises()->sync($choises);

            $answer->touch();
        } elseif ($choises) {
            $answer->choises()->sync(array($choises));

            $answer->touch();
        }
    }
}
