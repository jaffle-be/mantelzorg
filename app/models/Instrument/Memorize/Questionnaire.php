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

        return $this->oudere->find($id);
    }

    public function getOudere()
    {
        return $this->session->get('instrument.oudere');
    }

    public function panel(Panel $panel, $payload)
    {
        $this->session->set($this->panelKey($panel), $payload);
    }

    protected function panelKey(Panel $panel)
    {
        return 'instrument.panel.panel-' . $panel->id;
    }

} 