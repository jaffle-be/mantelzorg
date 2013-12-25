<?php

namespace Instrument\Engine;

class Question {

    public function render(\Questionnaire\Question $question)
    {
        return '<div class="col-md-offset-1 col-md-11 instrument-question">' . $question->question . '</div>';
    }

    public function wrapper($option){
        switch($option)
        {
            case 'open':
                return '<div class="row instrument-questions">';
                break;
            case 'close':
                return '</div>';
                break;
        }
    }

} 