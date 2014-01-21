<?php

namespace Instrument\Engine;

use Questionnaire\Panel;
use Questionnaire\Questionnaire;
use URL;
use Lang;

class Template {

    /**
     * @var Header
     */
    protected $header;

    /**
     * @var Question
     */
    protected $question;

    public function __construct(Header $header, Question $question)
    {
        $this->header = $header;

        $this->question = $question;
    }

    public function header(Questionnaire $questionnaire, Panel $panel)
    {
        return $this->header->render($questionnaire, $panel);
    }

    public function questions(Panel $panel, $survey)
    {
        $output = $this->question->wrapper('open');

        $counter = 0;

        foreach($panel->questions as $question)
        {
            $output .= $this->question->render($question, $survey, $counter == 0);

            $counter++;
        }

        $output .= $this->question->wrapper('close');

        return  $output;
    }

    public function footer(Questionnaire $questionnaire, Panel $current)
    {
        $next = $questionnaire->nextPanel($current);

        $output = '<div class="instrument-footer">';

        $output = '<input type="hidden" id="next_panel" name="next_panel"/>';

        $output .= sprintf('<input type="submit" class="btn btn-%s" value="%s">', $current->color ? $current->color : 'primary' ,$next ? $next->title : Lang::get('instrument.bevestigen'));

        $output .= '</div>';

        return $output;
    }

} 