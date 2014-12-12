<?php

namespace Beta;

use Eloquent;

class Registration extends Eloquent{

    protected $table = 'beta_registrations';

    protected $fillable = array('firstname', 'lastname', 'email', 'organisation');

    public function validator(array $input = array(), array $rules = array())
    {
        if(empty($input))
        {
            $input = \Input::all();
        }

        if(empty($rules))
        {
            $rules = static::$rules;
        }

        return \Validator::make($input, $rules);

    }

    protected static $rules = array(
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required|email|unique:beta_registrations',
        'organisation' => array('required', 'firm-name' => 'regex:/^[a-zA-Z09 -\.]+$/')
    );

}