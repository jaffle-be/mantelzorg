<?php

namespace Questionnaire;

use Eloquent;
use Input;
use Validator;

class Choise extends Eloquent
{

    protected $table = 'questionnaire_choises';

    protected $fillable = array('question_id', 'title', 'sort_weight');

    protected static $rules = array(
        'question_id' => 'required|exists:questionnaire_questions,id',
        'title'       => 'required',
        'sort_weight' => 'required|integer'
    );

    public function validator($input = null, $rules = null)
    {
        if (empty($input)) {
            $input = Input::all();
        }

        if (is_string($rules)) {
            $rules = array($rules);
        }

        $rules = array_intersect(static::$rules, array_flip($rules));

        return Validator::make($input, $rules);
    }

    public function question()
    {
        return $this->belongsTo('Questionnaire\Question', 'question_id');
    }
}