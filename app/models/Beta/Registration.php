<?php

namespace Beta;

use Eloquent, Input, Validator;
use Search\Model\Searchable;
use Search\Model\SearchableTrait;

class Registration extends Eloquent implements Searchable{

    use SearchableTrait;

    protected $table = 'beta_registrations';

    protected static $searchableMapping = [
        'created_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ],
        'updated_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ],
        'email' => [
            'type' => 'string',
            'analyzer' => 'email'
        ]
    ];

    protected $fillable = array('firstname', 'lastname', 'email', 'organisation');

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

    protected static $rules = array(
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required|email|unique:beta_registrations',
        'organisation' => array('required', 'firm-name' => 'regex:/^[a-zA-Z09 -\.]+$/')
    );

}