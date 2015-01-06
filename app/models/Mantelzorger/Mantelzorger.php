<?php

namespace Mantelzorger;

use Carbon\Carbon;
use Search\Model\Searchable;
use Search\Model\SearchableTrait;
use Validator;
use Input;
use Eloquent;

class Mantelzorger extends Eloquent implements Searchable
{

    use SearchableTrait;

    protected $table = 'mantelzorgers';

    protected static $rules = array(
        'email'           => 'email|unique:mantelzorgers,email',
        'birthday'        => 'date_format:d/m/Y',
        'hulpverlener_id' => 'required|exists:users,id'
    );

    protected $fillable = array(
        'identifier', 'email', 'firstname', 'lastname', 'male', 'street', 'postal',
        'city', 'birthday', 'phone', 'hulpverlener_id'
    );

    public function getDisplayNameAttribute()
    {
        if (!empty($this->firstname) || !empty($this->lastname)) {
            return trim($this->getFullnameAttribute());
        } else if (!empty($this->identifier)) {
            return $this->identifier;
        } else {
            return '#ID#' . $this->id;
        }
    }

    public function setEmailAttribute($value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->attributes['email'] = $value;
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function setBirthdayAttribute($value)
    {
        if(!empty($value))
        {
            $this->attributes['birthday'] = Carbon::createFromFormat('d/m/Y', $value);
        }
    }

    public function validator(array $input = array(), array $rules = array())
    {
        if (empty($input)) {
            $input = Input::all();
        }

        if (empty($rules)) {
            $rules = static::$rules;
        } else {
            if (!is_array($rules)) {
                $rules = array($rules);
            }

            $rules = array_intersect_key(static::$rules, array_flip($rules));
        }

        return Validator::make($input, $rules);
    }

    public function oudere()
    {
        return $this->hasMany('Mantelzorger\Oudere', 'mantelzorger_id');
    }

    public function hulpverlener()
    {
        return $this->belongsTo('User', 'hulpverlener_id');
    }

    public function surveys()
    {
        return $this->hasMany('Questionnaire\\Session', 'mantelzorger_id');
    }

    public function getDates()
    {
        $dates = parent::getDates();

        return array_merge($dates, array('birthday'));
    }
}