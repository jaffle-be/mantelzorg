<?php

namespace Mantelzorger;

use Carbon\Carbon;
use Questionnaire\Export\Exportable;
use Search\Model\Searchable;
use Search\Model\SearchableTrait;
use System\Database\Eloquent\Model;
use Validator;
use Input;

class Mantelzorger extends Model implements Searchable, Exportable
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

    /**
     * @return array
     */
    public function toExportArray()
    {
        return [
            'mantelzorger-id' => isset($this->attributes['id']) ? $this->attributes['id'] : null,
            'mantelzorger-identifier' => isset($this->attributes['identifier']) ? $this->attributes['identifier'] : null,
            'mantelzorger-email' => isset($this->attributes['email']) ? $this->attributes['email'] : null,
            'mantelzorger-firstname' => isset($this->attributes['firstname']) ? $this->attributes['firstname'] : null,
            'mantelzorger-lastname' => isset($this->attributes['lastname']) ? $this->attributes['lastname'] : null,
            'mantelzorger-male' => isset($this->attributes['male']) ? $this->attributes['male'] : null,
            'mantelzorger-street' => isset($this->attributes['street']) ? $this->attributes['street'] : null,
            'mantelzorger-postal' => isset($this->attributes['postal']) ? $this->attributes['postal'] : null,
            'mantelzorger-city' => isset($this->attributes['city']) ? $this->attributes['city'] : null,
            'mantelzorger-phone' => isset($this->attributes['phone']) ? $this->attributes['phone'] : null,
            'mantelzorger-birthday' => isset($this->attributes['birthday']) ? $this->attributes['birthday'] : null,
        ];
    }

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