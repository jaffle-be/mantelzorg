<?php

namespace Instrument\Engine;

use Illuminate\Database\Eloquent\Collection;
use Questionnaire\Panel;
use Questionnaire\Question as Q;
use Lang;
use Questionnaire\Session;

class Question
{

    public function render(Panel $panel, Q $question, Session $survey, $first)
    {
        $answer = $survey->getAnswered($question);

        $output = '<div class="row">';

        $output .= '<div class="col-xs-1 visible-xs"></div>';

        $filled = $answer && $answer->wasFilledIn();

        $mark = $filled ? 'display:none;' : null;

        $check = $filled ? null : 'display:none;';

        $output .= sprintf('<div class="col-md-1 col-xs-1 question-status question-%s"><i class="glyphicon glyphicon-question-sign" style="%s"></i><i class="glyphicon glyphicon-ok" style="%s"></i></div>', $panel->color, $mark, $check);

        $output .= sprintf('<div class="col-md-11 col-xs-9 instrument-question question-%s" data-question-id="%s">', $panel->color, $question->id);

        //wrapper to allow for a border
        $output .= '<div>';

        $output .= $this->header($question, $first);

        $output .= $this->body($question, $answer, $first);

        $output .= '</div></div></div>';

        return $output;
    }

    public function wrapper($option)
    {
        switch ($option) {
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

    protected function body(Q $question, $answer, $first)
    {
        $output = sprintf('<div class="body" %s>', $first ? '' : 'style="display:none;"');

        $output .= '<div class="question">' . $question->question . '</div>';

        if ($question->meta) {
            $output .= '<div class="well well-sm" style="display:none;">' . $question->meta . '</div>';
        }

        if ($question->multiple_choise == '1') {
            $output .= $this->multipleChoise($question, $answer);
        }

        if ($question->explainable == '1') {
            $output .= $this->explainable($question, $answer);
        }

        $output .= '</div>';

        return $output;
    }

    protected function multipleChoise(Q $question, $answer)
    {
        $output = $this->openChoises();

        if ($question->multiple_answer == '1') {
            $output .= $this->checkboxes($question, $answer);
        } else {
            $output .= $this->radios($question, $answer);
        }

        $output .= $this->closeChoises();

        return $output;
    }

    protected function explainable(Q $question, $answer)
    {
        $output = '<div class="explanation">';

        $output .= sprintf('<textarea name="explanation%s" class="form-control" placeholder="%s">%s</textarea>', $question->id, Lang::get('instrument.toelichting'), $answer ? $answer->explanation : null);

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

    protected function radios(Q $question, $answer)
    {
        $output = '';

        $identifier = 'question' . $question->id;

        foreach ($question->choises as $choise) {
            $checked = $answer && $answer->wasChecked($choise) ? 'checked="checked"' : null;

            $output .= sprintf('<li class="radio"> <label><input class="" type="radio" %s name="%s" value="%s"/>%s</label></li>', $checked, $identifier, $choise->id, $choise->title);
        }

        return $output;
    }

    protected function checkboxes(Q $question, $answer)
    {
        $output = '';

        $identifier = 'question' . $question->id;

        foreach ($question->choises as $choise) {
            $checked = $answer && $answer->wasChecked($choise) ? 'checked="checked"' : null;

            $output .= sprintf('<li class="checkbox"> <label><input class="" type="checkbox" %s name="%s[]" value="%s"/>%s</label></li>', $checked, $identifier, $choise->id, $choise->title);
        }

        return $output;
    }
}