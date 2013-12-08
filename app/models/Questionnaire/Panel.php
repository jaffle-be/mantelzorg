<?php

namespace Questionnaire;
use Eloquent;
use Input;
use Validator;

class Panel extends Eloquent{

    protected $table = 'questionnaire_panels';

    protected $fillable = array('questionnaire_id', 'title', 'panel_weight');

    protected static $rules = array(
        'questionnaire_id' => 'required|exists:questionnaires,id',
        'panel_weight' => 'required|int',
        'title' => 'required'
    );

    public function validator()
    {
        return Validator::make(Input::all(), static::$rules);
    }

    public function questions()
    {
        return $this->hasMany('Questionnaire\Question', 'questionnaire_panel_id');
    }

    public function questionnaire()
    {
        return $this->belongsTo('Questionnaire\Questionnaire', 'questionnaire_id');
    }

} 