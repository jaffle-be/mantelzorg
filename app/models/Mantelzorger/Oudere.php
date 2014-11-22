<?php

namespace Mantelzorger;

use DateTime;
use Input;
use Validator;

class Oudere extends \Eloquent{

    protected $table = 'ouderen';

    protected static $rules = array(
        'email' => 'email|unique:ouderen,email',
        'mantelzorger_id' => 'required|exists:mantelzorgers,id',
        'mantelzorger_relation' => 'exists:meta_values,id',
        'birthday' => 'date_format:d/m/Y',
    );

    protected $fillable = array(
        'identifier', 'email', 'firstname', 'lastname', 'male', 'street', 'postal', 'city',
        'phone', 'mantelzorger_id', 'birthday', 'diagnose', 'mantelzorger_relation'
    );

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function validator($input = array(), $rules = array())
    {
        if(empty($input))
        {
            $input = Input::all();
        }

        if(empty($rules))
        {
            $rules = static::$rules;
        }
        else
        {
            if(!is_array($rules)) $rules = array($rules);

            $rules = array_intersect_key(static::$rules, array_flip($rules));
        }

        return Validator::make($input, $rules);
    }

    public function setBirthdayAttribute($value)
    {
        if(!empty($value))
        {
            $this->attributes['birthday'] = DateTime::createFromFormat('d/m/Y', $value);
        }
    }

    public function mantelzorger_relation()
    {
        $this->belongsTo('Meta\Meta', 'mantelzorger_relation');
    }

    public function getDates()
    {
        return array_merge(parent::getDates(), array('birthday'));
    }


} 