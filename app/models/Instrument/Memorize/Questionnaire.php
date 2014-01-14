<?php

namespace Instrument\Memorize;

use Illuminate\Session\Store;
use Mantelzorger\Mantelzorger;
use Mantelzorger\Oudere;
use Questionnaire\Panel;
use Questionnaire\Question;
use Questionnaire\Answer;
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

    public function set(array $payload)
    {
        foreach($payload as $method => $data)
        {
            $this->$method($data);
        }
    }

    public function mantelzorger(Mantelzorger $mantelzorger)
    {
        $this->session->set('instrument.mantelzorger', $mantelzorger->id);
    }

    public function getMantelzorger()
    {
        $id = $this->session->get('instrument.mantelzorger');

        return $this->mantelzorger->find($id);
    }

    public function oudere(Oudere $oudere)
    {
        $this->session->set('instrument.oudere', $oudere->id);
    }

    public function getOudere()
    {
        $id = $this->session->get('instrument.oudere');

        return $this->oudere->find(id);
    }

    public function panel(Panel $panel, $payload)
    {
        $this->session->set($this->panelKey($panel), $payload);
    }

    protected function panelKey(Panel $panel)
    {
        return 'instrument.panel.panel-' . $panel->id;
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

    protected function answers()
    {
        if(!$this->session->has('answers'))
        {
            $answers = array();

            $this->session->set('answers', $answers);

        }
        return $this->session->get('answers');
    }

    protected function update($payload)
    {
        $this->session->set('answers', $payload);
    }

} 