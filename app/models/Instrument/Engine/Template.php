<?php

namespace Instrument\Engine;

use Questionnaire\Panel;
use URL;
use Lang;

class Template
{

    protected $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function questions(Panel $panel, $survey)
    {
        $output = $this->question->wrapper('open');

        $counter = 0;

        foreach ($panel->questions as $question) {
            $output .= $this->question->render($panel, $question, $survey, $counter == 0);

            $counter++;
        }

        $output .= $this->question->wrapper('close');

        return $output;
    }

    public function footer(Panel $panel)
    {
        $next = $panel->nextPanel();

        $output = '<div class="instrument-footer">';

        $output .= '<input type="hidden" id="next_panel" name="next_panel"/>';

        $output .= sprintf('<input type="submit" class="btn btn-%s" value="%s">', $panel->color ? $panel->color : 'primary', $next ? $next->title : Lang::get('instrument.bevestigen'));

        $output .= '</div>';

        return $output;
    }
}