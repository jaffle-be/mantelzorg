<?php

namespace Instrument\Engine;

use Questionnaire\Panel;
use Questionnaire\Questionnaire;

class Header {

    public function render(Panel $panel)
    {
        $output = $this->open();

        foreach($panel->questionnaire->panels as $item)
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
        $active = $panel->id == $current->id;

        return sprintf('<li class="%s %s"><a data-target-id="%s" href="">%s</a></li>', $active ? 'active' : '', $active ? 'panel-' . $panel->color : '', $panel->id, $panel->title);
    }

} 