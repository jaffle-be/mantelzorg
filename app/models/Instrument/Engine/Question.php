<?php

namespace Instrument\Engine;

use Illuminate\Database\Eloquent\Collection;
use Questionnaire\Question as Q;
use Lang;

class Question {

    public function render(Q $question, $survey, $first)
    {
        $survey->load(array('answers', 'answers.choises'));

        $output = '<div class="row">';

        $output .= sprintf('<div class="col-md-1 question-status question-%s"><i class="glyphicon glyphicon-question-sign"></i><i class="glyphicon glyphicon-ok" style="display:none;"></i></div>', $question->panel->color);

        $output .= sprintf('<div class="col-md-11 instrument-question question-%s" data-question-id="%s">', $question->panel->color, $question->id);

        //wrapper to allow for a border
        $output .= '<div>';

        $output .= $this->header($question, $first);

        $output .= $this->body($question, $survey, $first);

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
        return sprintf('<div class="header">%s <i class="glyphicon glyphicon-edit" %s></i><i title="%s" class="glyphicon glyphicon-comment" %s></i></div>', $question->title, $first ? 'style="display:none;"' : '', Lang::get('questionnaires.meta'), $first ? '' : 'style="display:none;"');
    }

    protected function body(Q $question, $survey, $first)
    {
        $output = sprintf('<div class="body" %s>', $first ? '' : 'style="display:none;"');

        $output .= '<div class="question">' . $question->question . '</div>';

        if($question->meta)
        {
            $output .= '<div class="well well-sm" style="display:none;">' . $question->meta . '</div>';
        }

        if($question->multiple_choise === '1')
        {
            $output .= $this->multipleChoise($question, $survey);
        }

        if($question->explainable === '1')
        {
            $output .= $this->explainable($question, $survey);
        }

        $output .= '</div>';

        return $output;
    }

    protected function multipleChoise(Q $question, $survey)
    {
        $output = $this->openChoises();

        if($question->multiple_answer === '1')
        {
            $output .= $this->checkboxes($question, $survey);
        }
        else
        {
            $output .= $this->radios($question, $survey);
        }

        $output .= $this->closeChoises();

        return $output;
    }

    protected function explainable(Q $question, $survey)
    {
        $answer = $survey->answers->filter(function($item) use ($question)
        {
            if($item->question->id === $question->id)
                return true;
        })->first();

        $output = '<div class="explanation">';

        $output .= sprintf('<textarea name="explanation%s" class="form-control" placeholder="%s">%s</textarea>', $question->id, Lang::get('instrument.toelichting'), $answer ? $answer->explanation : null) ;

        $output .= '</div>';

        return $output;
    }

    protected function checked($survey, $choise, $question)
    {
        $id = $choise->question->id;
        //find the answer for the question
        $answer = $survey->answers->filter(function($item) use ($id)
        {
            if($item->question_id === $id)
            {
                return true;
            }
        })->first();

        if($answer)
        {
            //find the choise for the answer
            $checked = $answer->choises->filter(function($item) use ($choise){
                if($item->id === $choise->id)
                    return true;
            })->first();

            if($checked)
            {
                return 'checked="checked"';
            }
        }
    }

    protected function openChoises()
    {
        return '<ul class="choises">';
    }

    protected function closeChoises()
    {
        return '</ul>';
    }

    protected function radios(Q $question, $survey)
    {
        $output = '';

        foreach($question->choises as $choise)
        {
            $output .= sprintf('<li class="radio"> <label><input class="" type="radio" %s name="%s" value="%s"/>%s</label></li>', $this->checked($survey, $choise, $question) ,'question' . $choise->question->id , $choise->id , $choise->title);
        }

        return $output;
    }


    protected function checkboxes(Q $question, $survey)
    {
        $output = '';

        foreach($question->choises as $choise)
        {
            $output .= sprintf('<li class="checkbox"> <label><input class="" type="checkbox" %s name="%s[]" value="%s"/>%s</label></li>', $this->checked($survey, $choise, $question), 'question' . $choise->question->id , $choise->id , $choise->title);
        }

        return $output;
    }

} 