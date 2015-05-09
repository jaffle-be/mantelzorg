<?php

namespace Mantelzorger;

use Carbon\Carbon;
use Input;
use Questionnaire\Export\Exportable;
use Search\Model\Searchable;
use Search\Model\SearchableTrait;
use System\Database\Eloquent\Model;
use Validator;

class Oudere extends Model implements Exportable
{

    protected $table = 'ouderen';

    protected static $rules = array(
        'identifier'            => 'required|unique:ouderen,identifier,#oudere,id,mantelzorger_id,#mantelzorger',
        'male'                  => 'required|in:0,1',
        'email'                 => 'email|unique:ouderen,email,#oudere,id,mantelzorger_id,#mantelzorger',
        'mantelzorger_id'       => 'required|exists:mantelzorgers,id',
        'mantelzorger_relation' => 'exists:meta_values,id',
        'birthday'              => 'required|date_format:d/m/Y',
        'woonsituatie_id'          => 'required|exists:meta_values,id',
        'oorzaak_hulpbehoefte_id'  => 'required|exists:meta_values,id'
    );

    protected $fillable = array(
        'identifier', 'email', 'firstname', 'lastname', 'male', 'street', 'postal', 'city',
        'phone', 'mantelzorger_id', 'birthday', 'diagnose', 'mantelzorger_relation_id', 'woonsituatie_id', 'oorzaak_hulpbehoefte_id', 'bel_profiel_id', 'details_diagnose',
    );

    /**
     * @return array
     */
    public function toExportArray()
    {
        return [
            'hulpbehoevende-id' => isset($this->attributes['id']) ? $this->attributes['id'] : null,
            'hulpbehoevende-identifier' => isset($this->attributes['identifier']) ? $this->attributes['identifier'] : null,
            'hulpbehoevende-email' => isset($this->attributes['email']) ? $this->attributes['email'] : null,
            'hulpbehoevende-firstname' => isset($this->attributes['firstname']) ? $this->attributes['firstname'] : null,
            'hulpbehoevende-lastname' => isset($this->attributes['lastname']) ? $this->attributes['lastname'] : null,
            'hulpbehoevende-male' => isset($this->attributes['male']) ? $this->attributes['male'] : null,
            'hulpbehoevende-street' => isset($this->attributes['street']) ? $this->attributes['street'] : null,
            'hulpbehoevende-postal' => isset($this->attributes['postal']) ? $this->attributes['postal'] : null,
            'hulpbehoevende-city' => isset($this->attributes['city']) ? $this->attributes['city'] : null,
            'hulpbehoevende-phone' => isset($this->attributes['phone']) ? $this->attributes['phone'] : null,
            'hulpbehoevende-birthday' => isset($this->attributes['birthday']) ? $this->attributes['birthday'] : null,
            'hulpbehoevende-diagnose' => isset($this->attributes['diagnose']) ? $this->attributes['diagnose'] : null,
//            'hulpbehoevende-mantelzorger_relation',
//            'hulpbehoevende-woonsituatie',
//            'hulpbehoevende-oorzaak_hulpbehoefte',
//            'hulpbehoevende-bel_profiel',
//            'hulpbehoevende-details_diagnose'
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

    //avoid mysql errors on setting an empty string
    public function setMantelzorgerRelationIdAttribute($value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->attributes['mantelzorger_relation_id'] = $value;
    }

    public function setWoonsituatieIdAttribute($value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->attributes['woonsituatie_id'] = $value;
    }

    public function setOorzaakHulpbehoefteIdAttribute($value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->attributes['oorzaak_hulpbehoefte_id'] = $value;
    }

    public function setBelProfielIdAttribute($value)
    {
        if (empty($value)) {
            $value = null;
        }

        $this->attributes['bel_profiel_id'] = $value;
    }

    public function getFullnameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function validator($input = array(), $rules = array(), array $placeholders = [])
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

    public function setBirthdayAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['birthday'] = Carbon::createFromFormat('d/m/Y', $value);
        }
    }

    public function mantelzorger()
    {
        return $this->belongsTo('Mantelzorger\Mantelzorger');
    }

    public function mantelzorger_relation()
    {
        return $this->belongsTo('Meta\Value', 'mantelzorger_relation_id');
    }

    public function oorzaak_hulpbehoefte()
    {
        return $this->belongsTo('Meta\Value', 'oorzaak_hulpbehoefte_id');
    }

    public function bel_profiel(){
        return $this->belongsTo('Meta\Value', 'bel_profiel_id');
    }

    public function woon_situatie()
    {
        return $this->belongsTo('Meta\Value', 'woonsituatie_id');
    }

    public function getDates()
    {
        return array_merge(parent::getDates(), array('birthday'));
    }
}