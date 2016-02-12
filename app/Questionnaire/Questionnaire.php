<?php

namespace App\Questionnaire;

use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;

class Questionnaire extends Model
{
    use ValidationRules;

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

    public function questions()
    {
        return $this->hasManyThrough('App\Questionnaire\Question', 'App\Questionnaire\Panel', 'questionnaire_id', 'questionnaire_panel_id');
    }

    public function panels()
    {
        return $this->hasMany('App\Questionnaire\Panel', 'questionnaire_id');
    }

    public function sessions()
    {
        return $this->hasMany('App\Questionnaire\Session');
    }

    public function nextPanel(Panel $panel)
    {
        $weight = $panel->panel_weight + 10;

        $panels = $this->panels->filter(function ($item) use ($weight) {
            return (int) $item->panel_weight == $weight;
        });

        if (count($panels)) {
            return $panels->first();
        } else {
            return false;
        }
    }
}
