<?php

namespace Questionnaire;

use Search\Model\Searchable;
use Search\Model\SearchableTrait;
use System\Database\Eloquent\Model;

class Session extends Model implements Searchable
{
    protected static $searchableMapping = [
        'created_at' => [
            'type'   => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ],
        'updated_at' => [
            'type'   => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ]
    ];

    use SearchableTrait;

    //map to cache answers to questions
    protected static $cache = [];

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

    public function user()
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
        if (isset(static::$cache[$question->id])) {
            return static::$cache[$question->id];
        }

        $answer = $this->answers->filter(function ($item) use ($question) {
            if ($item->getAttribute('question_id') == $question->getAttribute('id')) {
                return true;
            }
        })->first();

        static::$cache[$question->id] = $answer;

        return $answer;
    }

    /**
     * Return a string that helps identify this session
     *
     * @param string|null $name
     *
     * @return mixed
     */
    public function getIdentifier($name = null)
    {
        if (empty($name)) {
            $name = $this->questionnaire->title;
        }

        $key = $this->getKey();

        if (strlen($key) < 5) {
            $key = str_pad($key, 5, 0, STR_PAD_LEFT);
        }

        return $name . '-' . $key;
    }
}