<?php

namespace App\Questionnaire;

use Input;
use App\System\Database\Eloquent\Model;
use Validator;

class Panel extends Model
{

    protected $table = 'questionnaire_panels';

    protected $fillable = array('questionnaire_id', 'title', 'color', 'panel_weight');

    protected static $rules = array(
        'questionnaire_id' => 'required|exists:questionnaires,id',
        'panel_weight'     => 'required|int',
        'color'            => 'in:blue,red,green,gray,purple,yellow,green,orange',
        'title'            => 'required'
    );

    public function validator($input = array(), $fields = array())
    {
        $rules = array_intersect_key(static::$rules, array_flip($fields));

        if (empty($input)) {
            $input = Input::all();
        }

        return Validator::make($input, $rules);
    }

    public function questions()
    {
        return $this->hasMany('App\Questionnaire\Question', 'questionnaire_panel_id');
    }

    public function questionnaire()
    {
        return $this->belongsTo('App\Questionnaire\Questionnaire', 'questionnaire_id');
    }

    public function nextPanel()
    {
        return $this->questionnaire->nextPanel($this);
    }
}