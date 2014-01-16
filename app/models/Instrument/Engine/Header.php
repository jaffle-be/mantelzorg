<?php

namespace Instrument\Engine;

use Questionnaire\Panel;
use Questionnaire\Questionnaire;

class Header {

    public function render(Questionnaire $questionnaire, Panel $panel)
    {
        $output = $this->open();

        foreach($questionnaire->panels as $item)
        {
            $output .= $this->item($item, $panel);
        }

        $output .= $this->close();

        return $output;
    }

    protected function open()
    {
        return '<div class="row instrument-header"><ul>';
    }

    protected function close()
    {
        return '</ul></div>';
    }

    protected function item(Panel $panel, Panel $current)
    {
        return sprintf('<li class="%s %s">%s</li>', $panel->id === $current->id ? 'active' : '', 'panel-' . $panel->color, $panel->title);
    }

} 