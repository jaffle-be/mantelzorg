<?php

namespace Questionnaire;
use Eloquent;
use Input;
use Validator;

class Answer extends Eloquent{

    protected $table = 'questionnaire_answers';

    protected $fillable = array('question_id', 'explanation');

    protected static $rules = array(
        'question_id' => 'required|exists:questionnaire_questions,id',
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

    public function choises()
    {
        return $this->belongsToMany('Questionnaire\Choise', 'questionnaire_answer_choises', 'answer_id', 'choise_id');
    }

}