<?php

namespace Mantelzorger;

use Validator;
use Input;
use Eloquent;
use DateTime;

class Mantelzorger extends Eloquent{

    protected $table = 'mantelzorgers';

    protected static $rules = array(
        'email' => 'email|unique:mantelzorgers,email|required',
        'firstname' => 'required',
        'lastname' => 'required',
        'male' => 'required',
        'street' => 'required',
        'postal' => 'required',
        'city' => 'required',
        'birthday' => 'required|date_format:d/m/Y',
        'hulpverlener_id' => 'required|exists:users,id'
    );

    protected $fillable = array(
        'email', 'firstname', 'lastname', 'male', 'street', 'postal',
        'city', 'birthday', 'phone', 'hulpverlener_id'
    );

    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = DateTime::createFromFormat('d/m/Y', $value);
    }

    public function validator(array $input = array(), array $rules = array())
    {
        if(empty($input))
        {
            $input = Input::all();
        }
        if(empty($rules))
        {
            $rules = static::$rules;
        }

        return Validator::make($input, $rules);
    }

    public function oudere()
    {
        return $this->hasMany('Mantelzorger\Oudere', 'mantelzorger_id');
    }

    public function hulpverlener()
    {
        return $this->belongsTo('User', 'hulpverlener_id');
    }

} 