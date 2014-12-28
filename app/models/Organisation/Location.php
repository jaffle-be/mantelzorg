<?php

namespace Organisation;

use Eloquent;
use Input;
use Validator;

class Location extends Eloquent
{

    protected $table = 'locations';

    protected static $rules = array(
        'name'            => 'required',
        'street'          => 'required',
        'postal'          => 'required',
        'city'            => 'required',
        'organisation_id' => 'required|exists:organisations,id'
    );

    protected $fillable = array(
        'name', 'street', 'postal', 'city', 'country', 'organisation_id'
    );

    public function validator($rules = null, $input = null)
    {
        if (empty($rules)) {
            $rules = static::$rules;
        }
        if (empty($input)) {
            $input = Input::all();
        }

        return Validator::make($input, $rules);
    }

    public function organisation()
    {
        return $this->belongsTo('Organisation\\Organisation');
    }
}