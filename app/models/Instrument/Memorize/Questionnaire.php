<?php

namespace Instrument\Memorize;

use Illuminate\Session\Store;
use Mantelzorger\Mantelzorger;
use Mantelzorger\Oudere;
use Questionnaire\Panel;

class Questionnaire {

    protected $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
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

    public function oudere(Oudere $oudere)
    {
        $this->session->set('instrument.oudere', $oudere->id);
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