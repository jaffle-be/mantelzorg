<?php

namespace Instrument\Engine;

use Questionnaire\Panel;
use Questionnaire\Questionnaire;

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

        foreach($panel->questions as $question)
        {
            $output .= $this->question->render($question);
        }

        $output .= $this->question->wrapper('close');

        return  $output;
    }

} 