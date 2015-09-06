<?php

namespace App\Beta;

use App\Search\Model\Searchable;
use App\Search\Model\SearchableTrait;
use App\System\Database\Eloquent\Model;
use Input;
use Validator;

class Registration extends Model implements Searchable
{

    use SearchableTrait;

    protected $table = 'beta_registrations';

    protected static $searchableMapping = [
        'created_at' => [
            'type'   => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ],
        'updated_at' => [
            'type'   => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ],
        'email'      => [
            'type'     => 'string',
            'analyzer' => 'email'
        ]
    ];

    protected $fillable = array('firstname', 'lastname', 'email', 'organisation');

    public function validator(array $input = array(), array $rules = array())
    {
        if (empty($input)) {
            $input = Input::all();
        }

        if (empty($rules)) {
            $rules = static::$rules;
        }

        return Validator::make($input, $rules);
    }

    protected static $rules = array(
        'firstname'    => 'required',
        'lastname'     => 'required',
        'email'        => 'required|email|unique:beta_registrations',
        'organisation' => array('required', 'firm-name' => 'regex:/^[a-zA-Z09 -\.]+$/')
    );

}