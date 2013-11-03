<?php

class Admin extends Eloquent{

    protected $rules = array(
        'email' => 'required|email|unique:admins',
        'firstname' => 'required',
        'lastname' => 'required',
        'active' => 'boolean'
    );

    protected $fillable = array('email', 'firstname', 'lastname', 'password', 'active');

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

}