<?php

namespace Mantelzorger;

class Oudere extends \Eloquent{

    protected $table = 'ouderen';

    protected static $rules = array(
        'email' => 'email|unique:ouderen,email',
        'firstname' => 'required',
        'lastname' => 'required',
        'male' => 'required',
        'street' => 'required',
        'postal' => 'required',
        'city' => 'required',
        'mantelzorger_id' => 'required|exists:mantelzorgers,id',
        'birthday' => 'required',
    );

    protected $fillable = array(
        'email', 'firstname', 'lastname', 'male', 'street', 'postal', 'city',
        'phone', 'mantelzorger_id', 'birthday',
    );

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

        return \Validator::make($input, $rules);
    }


} 