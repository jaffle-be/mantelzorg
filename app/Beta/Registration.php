<?php

namespace App\Beta;

use App\Search\Model\Searchable;
use App\Search\Model\SearchableTrait;
use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;

class Registration extends Model implements Searchable
{
    use SearchableTrait, ValidationRules;

    protected $table = 'beta_registrations';

    protected static $searchableMapping = [
        'created_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss',
        ],
        'updated_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss',
        ],
        'email' => [
            'type' => 'string',
            'analyzer' => 'email',
        ],
    ];

    protected $fillable = array('firstname', 'lastname', 'email', 'organisation');

    protected static $rules = array(
        'firstname' => 'required',
        'lastname' => 'required',
        'email' => 'required|email|unique:beta_registrations',
        'organisation' => 'required',
    );
}
