<?php

namespace Mantelzorger;

class Oudere extends \Eloquent{

    protected $table = 'ouderen';

    protected static $rules = array(
        'email' => 'required|email|unique:ouderen,email',
        'firstname' => 'required',
        'lastname' => 'required',
        'male' => 'required',
        'street' => 'required',
        'postal' => 'required',
        'city' => 'required',
        'mantelzorger_id' => 'required|exists:ouderen,id'
    );

    protected $fillable = array(
        'email', 'firstname', 'lastname', 'male', 'street', 'postal', 'city',
        'phone', 'mantelzorger'
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