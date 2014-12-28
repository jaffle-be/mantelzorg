<?php

namespace Questionnaire;

use Eloquent;
use Input;
use Validator;

class Answer extends Eloquent
{

    protected $table = 'questionnaire_answers';

    protected $fillable = array('question_id', 'explanation', 'session_id');

    protected static $rules = array(
        'session_id'  => 'required|exists:questionnaire_survey_sessions,id',
        'question_id' => 'required|exists:questionnaire_questions,id',
        'explanation'
    );

    public function validator()
    {
        return Validator::make(Input::all(), static::$rules);
    }

    public function session()
    {
        return $this->belongsTo('Questionnaire\Session', 'session_id');
    }

    public function question()
    {
        return $this->belongsTo('Questionnaire\Question', 'question_id');
    }

    public function choises()
    {
        return $this->belongsToMany('Questionnaire\Choise', 'questionnaire_answer_choises', 'answer_id', 'choise_id')->withTimestamps();
    }

    public function wasFilledIn()
    {
        return !empty($this->explanation) || $this->choises->count() > 0;
    }

    public function wasChecked(Choise $choise)
    {
        return $this->choises->filter(function ($item) use ($choise) {
            if ($item->id == $choise->id) {
                return true;
            }
        })->first();
    }
}