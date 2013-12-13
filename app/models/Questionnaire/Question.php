<?php

namespace Questionnaire;
use Eloquent;
use Input;
use Validator;

class Question extends Eloquent{

    protected $table = 'questionnaire_questions';

    protected $fillable = array('questionnaire_id', 'questionnaire_panel_id', 'question', 'multiple_choise', 'summary_question', 'explainable');

    protected static $rules = array(
        'questionnaire_id' => 'required|exists:questionnaires,id',
        'questionnaire_panel_id' => 'required|exists:questionnaire_panels,id',
        'question' => 'required',
        'multiple_choise' => 'in:0,1',
        'summary_question' => 'in:0,1',
        'explainable' => 'in:0,1',
    );

    public function validator($input = null, $rules = null)
    {
        if(empty($input))
        {
            $input = Input::all();
        }

        return Validator::make($input, static::$rules);
    }

    public function answers()
    {
        return $this->hasMany('Questionnaire\Answer', 'question_id');
    }

    public function panel()
    {
        return $this->belongsTo('Questionnaire\Panel', 'questionnaire_panel_id');
    }

    public function questionnaire()
    {
        return $this->belongsTo('Questionnaire\Questionnaire', 'questionnaire_id');
    }

    public function choises()
    {
        return $this->hasMany('Questionnaire\Choise', 'question_id');
    }

} 