<?php
namespace Organisation;
use Input;
use Validator;
use Eloquent;

class Organisation extends Eloquent{

    protected $table = 'organisations';

    protected $fillable = array(
        'name'
    );

    protected static $rules = array(
        'name' => 'required|unique:organisations'
    );

    public function validator($rules = null, $input = null)
    {
        if(empty($rules))
        {
            $rules = static::$rules;
        }
        if(empty($input))
        {
            $input = Input::all();
        }

        return Validator::make($input, $rules);
    }

    public function locations()
    {
        return $this->hasMany('Organisation\\Location');
    }

} 