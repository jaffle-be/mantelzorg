<?php

namespace Mantelzorger;

use Carbon\Carbon;
use Input;
use Search\Model\Searchable;
use Search\Model\SearchableTrait;
use Validator;

class Oudere extends \Eloquent
{

    protected $table = 'ouderen';

    protected static $rules = array(
        'identifier'            => 'required|unique:ouderen,identifier,#oudere,id,mantelzorger_id,#mantelzorger',
        'male'                  => 'required|in:0,1',
        'email'                 => 'email|unique:ouderen,email,#oudere,id,mantelzorger_id,#mantelzorger',
        'mantelzorger_id'       => 'required|exists:mantelzorgers,id',
        'mantelzorger_relation' => 'exists:meta_values,id',
        'birthday'              => 'required|date_format:d/m/Y',
        'woonsituatie'          => 'required|exists:meta_values,id',
        'oorzaak_hulpbehoefte'  => 'required|exists:meta_values,id'
    );

    protected $fillable = array(
        'identifier', 'email', 'firstname', 'lastname', 'male', 'street', 'postal', 'city',
        'phone', 'mantelzorger_id', 'birthday', 'diagnose', 'mantelzorger_relation', 'woonsituatie', 'oorzaak_hulpbehoefte', 'bel_profiel',
    );

    public function getDisplayNameAttribute()
    {
        if (!empty($this->firstname) || !empty($this->lastname)) {
            return trim($this->getFullnameAttribute());
        } else if (!empty($this->identifier)) {
            return $this->identifier;
        } else {
            return '#ID#' . $this->id;
        }
    }

    public function setEmailAttribute($value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->attributes['email'] = $value;
    }

    public function setWoonsituatieAttribute($value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->attributes['woonsituatie'] = $value;
    }

    public function setOorzaakHulpbehoefteAttribute($value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->attributes['oorzaak_hulpbehoefte'] = $value;
    }

    public function setBelProfielAttribute($value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->attributes['bel_profiel'] = $value;
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function validator($input = array(), $rules = array(), array $placeholders = [])
    {
        if (empty($input)) {
            $input = Input::all();
        }

        $rules = array_merge($rules, static::$rules);

        array_walk($rules, function (&$rule) use ($placeholders) {
            foreach ($placeholders as $placeholder => $value) {
                $rule = str_replace('#' . $placeholder, $value, $rule);
            }
        });

        array_walk($rules, function (&$rule) {
            $rule = preg_replace('/#[^,]+/', 'NULL', $rule);
        });

        return Validator::make($input, $rules);
    }

    public function setBirthdayAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['birthday'] = Carbon::createFromFormat('d/m/Y', $value);
        }
    }

    public function mantelzorger()
    {
        return $this->belongsTo('Mantelzorger\Mantelzorger');
    }

    public function mantelzorger_relation()
    {
        return $this->belongsTo('Meta\Meta', 'mantelzorger_relation');
    }

    public function oorzaak_hulpbehoefte()
    {
        return $this->belongsTo('Meta\Value', 'oorzaak_hulpbehoefte');
    }

    public function getDates()
    {
        return array_merge(parent::getDates(), array('birthday'));
    }
}