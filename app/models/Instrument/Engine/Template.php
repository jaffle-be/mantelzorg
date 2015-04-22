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
}