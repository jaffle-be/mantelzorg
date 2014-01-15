<?php

namespace Instrument\Engine;

use Illuminate\Database\Eloquent\Collection;
use Questionnaire\Question as Q;
use Lang;

class Question {

    public function render(Q $question, $first)
    {
        $output = '<div class="row">';

        $output .= sprintf('<div class="col-md-1 question-status question-%s"><i class="glyphicon glyphicon-question-sign"></i><i class="glyphicon glyphicon-ok" style="display:none;"></i></div>', $question->panel->color);

        $output .= sprintf('<div class="col-md-11 instrument-question question-%s" data-question-id="%s">', $question->panel->color, $question->id);

        //wrapper to allow for a border
        $output .= '<div>';

        $output .= $this->header($question, $first);

        $output .= $this->body($question, $first);

        $output .= '</div></div></div>';

        return $output;
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

    protected function header(Q $question, $first)
    {
        return sprintf('<div class="header">%s <i class="glyphicon glyphicon-edit" %s></i><i class="glyphicon glyphicon-comment" %s></i></div>', $question->title, $first ? 'style="display:none;"' : '', $first ? '' : 'style="display:none;"');
    }

    protected function body(Q $question, $first)
    {
        $output = sprintf('<div class="body" %s>', $first ? '' : 'style="display:none;"');

        $output .= '<div class="question">' . $question->question . '</div>';

        $output .= '<div class="well well-sm" style="display:none;">' . $question->meta . '</div>';

        if($question->multiple_choise === '1')
        {
            $output .= $this->multipleChoise($question);
        }

        if($question->explainable === '1')
        {
            $output .= $this->explainable($question);
        }

        $output .= '</div>';

        return $output;
    }

    protected function multipleChoise(Q $question)
    {
        $output = $this->openChoises();

        if($question->multiple_answer === '1')
        {
            $output .= $this->checkboxes($question->choises);
        }
        else
        {
            $output .= $this->radios($question->choises);
        }

        $output .= $this->closeChoises();

        return $output;
    }

    protected function explainable(Q $question)
    {
        $output = '<div class="explanation">';

        $output .= sprintf('<textarea name="explanation%s" class="form-control" placeholder="%s"></textarea>', $question->id, Lang::get('instrument.toelichting')) ;

        $output .= '</div>';

        return $output;
    }

    protected function openChoises()
    {
        return '<ul class="choises">';
    }

    protected function closeChoises()
    {
        return '</ul>';
    }

    protected function radios(Collection $choises)
    {
        $output = '';

        foreach($choises as $choise)
        {
            $output .= sprintf('<li class="radio"> <label><input class="" type="radio" name="%s" value="%s"/>%s</label></li>', 'question' . $choise->question->id , $choise->id , $choise->title);
        }

        return $output;
    }


    protected function checkboxes(Collection $choises)
    {
        $output = '';

        foreach($choises as $choise)
        {
            $output .= sprintf('<li class="checkbox"> <label><input class="" type="checkbox" name="%s[]" value="%s"/>%s</label></li>', 'question' . $choise->question->id , $choise->id , $choise->title);
        }

        return $output;
    }

} 