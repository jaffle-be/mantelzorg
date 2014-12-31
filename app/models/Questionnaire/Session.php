<?php

namespace Questionnaire;

use Eloquent;
use Search\Model\Searchable;
use Search\Model\SearchableTrait;

class Session extends Eloquent implements Searchable
{

    use SearchableTrait;

    //override the searchable name to shorten it
    public function getSearchableType()
    {
        return 'surveys';
    }

    protected $table = "questionnaire_survey_sessions";

    protected $fillable = array('user_id', 'mantelzorger_id', 'oudere_id', 'questionnaire_id');

    protected static $rules = array(
        'user_id'          => 'required|exists:users,id',
        'mantelzorger_id'  => 'required|exists:mantelzorgers,id',
        'oudere_id'        => 'required|exists:ouderen,id',
        'questionnaire_id' => 'required|exists:questionnaires,id'
    );

    public function hulpverlener()
    {
        return $this->belongsTo('User');
    }

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

    public function questionnaire()
    {
        return $this->belongsTo('Questionnaire\Questionnaire');
    }

    /**
     * Checks to see if the survey has an answer to the given question
     *
     * @param Question $question
     *
     * @return Answer
     */
    public function getAnswered(Question $question)
    {
        return $this->answers->filter(function ($item) use ($question) {
            if ($item->question_id == $question->id) {
                return true;
            }
        })->first();
    }
}