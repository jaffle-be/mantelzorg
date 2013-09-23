<?php

namespace Beta;

use Eloquent;
use Twarlop\Support\Eloquent\Traits;


class Registration extends Eloquent{

    protected $table = 'beta_registrations';

    protected $fillable = array('firstname', 'sirname', 'email', 'organisation');

    use Traits\Validation;

    protected static $rules = array(
        'firstname' => 'required|alpha',
        'sirname' => 'required|alpha',
        'email' => 'required|email|unique:beta_registrations',
        'organisation' => array('required', 'firm-name' => 'regex:/^[a-zA-Z09 -\.]+$/')
    );

}