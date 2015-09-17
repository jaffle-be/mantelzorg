<?php

namespace App\Questionnaire;

use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;
use Input;
use Validator;

class Choise extends Model
{
    use ValidationRules;

    protected $table = 'questionnaire_choises';

    protected $fillable = array('question_id', 'title', 'sort_weight');

    protected static $rules = array(
        'question_id' => 'required|exists:questionnaire_questions,id',
        'title'       => 'required',
        'sort_weight' => 'required|integer'
    );

    public function question()
    {
        return $this->belongsTo('App\Questionnaire\Question', 'question_id');
    }
}