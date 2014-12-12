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

    public function scopeActive($query)
    {
        $query->where('active', '1');
    }

    public function validator($input = null, $fields = array())
    {
        if(empty($input))
        {
            $input = Input::all();
        }

        if(is_string($fields))
        {
            $fields = array($fields);
        }

        $rules = array_intersect_key(static::$rules, array_flip($fields));

        return Validator::make($input, $rules);
    }

    public function panels()
    {
        return $this->hasMany('Questionnaire\Panel', 'questionnaire_id');
    }

    public function nextPanel(Panel $panel)
    {
        $weight = $panel->panel_weight + 10;

        $panels = $this->panels->filter(function($item) use ($weight){
            return (int) $item->panel_weight == $weight;
        });

        if(count($panels))
        {
            return $panels->first();
        }
        else
            return false;
    }

}