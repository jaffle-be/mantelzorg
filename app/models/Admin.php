<?php

use Twarlop\Support\Eloquent\Traits\Validation;

class Admin extends Eloquent{

    protected $rules = array(
        'email' => 'required|email|unique:admins',
        'firstname' => 'required',
        'lastname' => 'required',
        'active' => 'boolean'
    );

    protected $fillable = array('email', 'firstname', 'lastname', 'password', 'active');

    use Validation;

}