<?php

namespace Instrument\Engine;

use Questionnaire\Panel;
use Questionnaire\Questionnaire;
use URL;

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

    public function questions(Panel $panel)
    {
        $output = $this->question->wrapper('open');

        $counter = 0;

        foreach($panel->questions as $question)
        {
            $output .= $this->question->render($question, $counter == 0);

            $counter++;
        }

        $output .= $this->question->wrapper('close');

        return  $output;
    }

    public function footer(Questionnaire $questionnaire, Panel $current)
    {
        $next = $questionnaire->nextPanel($current);

        if($next)
        {
            return sprintf('<div class="instrument-footer"><a class="btn btn-primary" href="%s">%s</a></div>', URL::route('instrument.panel.get', array($next->id) ), $next->title);
        }

        else
        {

        }


    }

} 