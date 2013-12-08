<?php

namespace Questionnaire;
use Eloquent;
use Input;
use Validator;

class Answer extends Eloquent{

    protected $table = 'questionnaire_answers';

    protected $fillable = array('question_id', 'answer_id', 'explanation');

    protected static $rules = array(
        'question_id' => 'required|exists:questionnaire_questions,id',
        'answer_id' => 'exists|questionnaire_answers,id',
        'explanation'
    );

    public function validator()
    {
        return Validator::make(Input::all(), static::$rules);
    }

    public function question()
    {
        return $this->belongsTo('Questionnaire\Question', 'question_id');
    }

    public function answer()
    {
        return $this->belongsTo('Questionnaire\Choise', 'answer_id');
    }

}