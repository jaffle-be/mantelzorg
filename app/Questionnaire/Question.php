<?php

namespace App\Questionnaire;

use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;
use Input;
use Validator;

class Question extends Model
{
    use ValidationRules;

    protected $table = 'questionnaire_questions';

    protected $fillable = array('questionnaire_id', 'questionnaire_panel_id', 'title', 'question', 'multiple_choise', 'multiple_answer', 'summary_question', 'explainable', 'meta');

    protected static $rules = array(
        'questionnaire_id'       => 'required|exists:questionnaires,id',
        'questionnaire_panel_id' => 'required|exists:questionnaire_panels,id',
        'title'                  => 'required',
        'question'               => 'required',
        'multiple_choise'        => 'in:0,1',
        'multiple_answer'        => 'in:0,1',
        'summary_question'       => 'in:0,1',
        'explainable'            => 'in:0,1',
    );

    public function answers()
    {
        return $this->hasMany('App\Questionnaire\Answer', 'question_id');
    }

    public function panel()
    {
        return $this->belongsTo('App\Questionnaire\Panel', 'questionnaire_panel_id');
    }

    public function questionnaire()
    {
        return $this->belongsTo('App\Questionnaire\Questionnaire', 'questionnaire_id');
    }

    public function choises()
    {
        return $this->hasMany('App\Questionnaire\Choise', 'question_id');
    }
}