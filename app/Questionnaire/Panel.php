<?php

namespace App\Questionnaire;

use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;
use App\System\Scopes\ModelAutoSort;
use Input;
use Validator;

class Panel extends Model
{
    use ValidationRules, ModelAutoSort;

    protected $table = 'questionnaire_panels';

    protected $fillable = array('questionnaire_id', 'title', 'color', 'panel_weight');

    public $autosort = 'panel_weight';

    protected static $rules = array(
        'questionnaire_id' => 'required|exists:questionnaires,id',
        'panel_weight'     => 'required|integer',
        'color'            => 'in:blue,red,green,gray,purple,yellow,green,orange',
        'title'            => 'required|unique:questionnaire_panels,title,#panel,id,questionnaire_id,#questionnaire'
    );

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