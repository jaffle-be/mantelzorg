<?php

namespace Questionnaire;
use Eloquent;


class Session extends Eloquent{

    protected $table = "questionnaire_survey_sessions";

    protected $fillable = array('mantelzorger_id', 'oudere_id');

    protected static $rules = array(
        'mantelzorger_id' => 'required|exists:mantelzorgers,id',
        'oudere_id' => 'required|exists:ouderen,id'
    );

    public function mantelzorger()
    {
        return $this->belongsTo('Mantelzorger\Mantelzorger');
    }

    public function oudere()
    {
        return $this->belongsTo('Mantelzorger\Oudere');
    }

    public function answers()
    {
        return $this->hasMany('Questionnaire\Answer');
    }

} 