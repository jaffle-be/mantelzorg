<?php

namespace Questionnaire;
use Eloquent;
use Input;
use Validator;

class Choise extends Eloquent{

    protected $table = 'questionnaire_choises';

    protected $fillable = array('question_id', 'title', 'value', 'sort_weight');

    protected static $rules = array(
        'question_id' => 'required|exists:questionnaire_questions,id',
        'title' => 'required',
        'value' => 'required|int',
        'sort_weight' => 'required|int'
    );

    public function validator()
    {
        return Validator::make(Input::all(), static::$rules);
    }

    public function question()
    {
        return $this->belongsTo('Questionnaire\Question', 'question_id');
    }

} 