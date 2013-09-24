<?php
use Eloquent;
use Twarlop\Support\Eloquent\Traits\Validation;

class Admin extends Eloquent{

    protected $rules = array(
        'email' => 'required|email|unique:admins',
        'firstname' => 'required|alpha',
        'lastname' => 'required|alpha',
        'active' => 'boolean'
    );

    protected $fillable = array('email', 'firstname', 'lastname', 'password', 'active');

    use Validation;

}