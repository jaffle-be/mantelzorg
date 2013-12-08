<?php
namespace Questionnaire;

use Eloquent;
use Validator;
use Input;

class Questionnaire extends Eloquent{

    protected $table = 'questionnaires';

    protected $fillable = array('title', 'active');

    protected static $rules = array(
        'title' => 'required|unique:questionnaires,title',
        'active' => 'in:0,1',
    );

    public function validator()
    {
        return Validator::make(Input::all(), static::$rules);
    }

} 