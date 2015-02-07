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
        'identifier'      => 'required|unique:mantelzorgers,identifier,#mantelzorger,id,hulpverlener_id,#hulpverlener',
        'male'            => 'required|in:0,1',
        'email'           => 'email|unique:mantelzorgers,email,#mantelzorger,id,hulpverlener_id,#hulpverlener',
        'birthday'        => 'required|date_format:d/m/Y',
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
        if (!empty($value)) {
            $this->attributes['birthday'] = Carbon::createFromFormat('d/m/Y', $value);
        }
    }

    public function validator(array $input = [], array $rules = [], array $placeholders = [])
    {
        if (empty($input)) {
            $input = Input::all();
        }

        $rules = array_merge($rules, static::$rules);

        array_walk($rules, function (&$rule) use ($placeholders) {
            foreach ($placeholders as $placeholder => $value) {
                $rule = str_replace('#' . $placeholder, $value, $rule);
            }
        });

        array_walk($rules, function (&$rule) {
            $rule = preg_replace('/#[^,]+/', 'NULL', $rule);
        });

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
        return array_merge(parent::getDates(), array('birthday'));
    }
}