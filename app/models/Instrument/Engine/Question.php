<?php

namespace Instrument\Engine;

class Question {

    public function render(\Questionnaire\Question $question)
    {
        return '<p>' . $question->question . '</p>';
    }

    public function wrapper($option){
        switch($option)
        {
            case 'open':
                return '<div class="instrument-questions">';
                break;
            case 'close':
                return '</div>';
                break;
        }
    }

} 