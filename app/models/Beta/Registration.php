<?php

namespace Beta;

use Eloquent;
use Jaffle\Support\Eloquent\Traits;


class Registration extends Eloquent{

    protected $table = 'beta_registrations';

    protected $fillable = array('firstname', 'lastname', 'email', 'organisation');

    use Traits\Validation;

    protected static $rules = array(
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required|email|unique:beta_registrations',
        'organisation' => array('required', 'firm-name' => 'regex:/^[a-zA-Z09 -\.]+$/')
    );

}