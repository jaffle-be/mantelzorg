<?php

namespace App\Mantelzorger;

use App\Questionnaire\Export\Exportable;
use App\Search\Model\Searchable;
use App\Search\Model\SearchableTrait;
use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;
use Carbon\Carbon;
use Input;
use Validator;

class Oudere extends Model implements Searchable, Exportable
{

    use SearchableTrait;
    use ValidationRules;

    protected $table = 'ouderen';

    protected static $searchableMapping = [
        'male'       => [
            'type' => 'boolean',
        ],
        'email'      => [
            'type'     => 'string',
            'analyzer' => 'email'
        ],
        "identifier" => [
            'type' => 'string',
            'fields' => [
                'raw' => [
                    'type' => 'string',
                    'index' => 'not_analyzed'
                ]
            ]
        ],
        'created_at' => [
            'type'   => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ],
        'updated_at' => [
            'type'   => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ],
    ];


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
            'hulpbehoevende-mantelzorger_relation' => $this->mantelzorger_relation ? $this->mantelzorger_relation->value : null,
            'hulpbehoevende-woonsituatie' => $this->woon_situatie ? $this->woon_situatie->value : null,
            'hulpbehoevende-oorzaak_hulpbehoefte' => $this->oorzaak_hulpbehoefte ? $this->oorzaak_hulpbehoefte->value : null,
            'hulpbehoevende-bel_profiel' => $this->bel_profiel ? $this->bel_profiel->value : null,
            'hulpbehoevende-details_diagnose' => isset($this->attributes['details_diagnose']) ? $this->attributes['details_diagnose'] : null
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
    public function setBirthdayAttribute($value)
    {
        if (!empty($value)) {

            if(preg_match('/(\d{1,2}\/){2}\d{4}/', $value))
            {
                $this->attributes['birthday'] = Carbon::createFromFormat('d/m/Y', $value);
            }
            else{
                $this->attributes['birtday'] = Carbon::createFromFormat('Y-m-d H:i:s', $value);
            }
        }
    }

    public function mantelzorger()
    {
        return $this->belongsTo('App\Mantelzorger\Mantelzorger');
    }

    public function mantelzorgerRelation()
    {
        return $this->belongsTo('App\Meta\Value', 'mantelzorger_relation_id');
    }

    public function oorzaakHulpbehoefte()
    {
        return $this->belongsTo('App\Meta\Value', 'oorzaak_hulpbehoefte_id');
    }

    public function belProfiel(){
        return $this->belongsTo('App\Meta\Value', 'bel_profiel_id');
    }

    public function woonSituatie()
    {
        return $this->belongsTo('App\Meta\Value', 'woonsituatie_id');
    }

    public function getDates()
    {
        return array_merge(parent::getDates(), array('birthday'));
    }

    public function scopeByName($query){
        $query->orderBy('identifier');
    }
}