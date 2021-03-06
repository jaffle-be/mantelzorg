<?php

namespace App\Mantelzorger;

use App\HandleBirthdayTrait;
use App\Questionnaire\Export\Exportable;
use App\Search\Model\Searchable;
use App\Search\Model\SearchableTrait;
use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;

class Mantelzorger extends Model implements Searchable, Exportable
{
    use SearchableTrait;
    use MantelzorgerSkills;
    use ValidationRules;
    use HandleBirthDayTrait;

    protected static $searchableMapping = [
        'male' => [
            'type' => 'boolean',
        ],
        'email' => [
            'type' => 'string',
            'analyzer' => 'email',
        ],
        'identifier' => [
            'type' => 'string',
            'fields' => [
                'raw' => [
                    'type' => 'string',
                    'index' => 'not_analyzed',
                ],
            ],
        ],
        'created_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss',
        ],
        'updated_at' => [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss',
        ],
    ];

    protected $table = 'mantelzorgers';

    protected static $rules = array(
        'identifier' => 'required|unique:mantelzorgers,identifier,#mantelzorger,id,hulpverlener_id,#hulpverlener',
        'male' => 'required|in:0,1',
        'email' => 'email|unique:mantelzorgers,email,#mantelzorger,id,hulpverlener_id,#hulpverlener',
        'birthday' => 'required|date_format:d/m/Y',
        'hulpverlener_id' => 'required|exists:users,id',
    );

    protected $fillable = array(
        'identifier', 'email', 'firstname', 'lastname', 'male', 'street', 'postal',
        'city', 'birthday', 'phone', 'hulpverlener_id',
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
            'mantelzorger-birthday-year' => isset($this->attributes['birthday']) ? $this->birthday->format('Y') : null,
        ];
    }

    public function getDisplayNameAttribute()
    {
        if (!empty($this->firstname) || !empty($this->lastname)) {
            return trim($this->getFullnameAttribute());
        } elseif (!empty($this->identifier)) {
            return $this->identifier;
        } else {
            return '#ID#'.$this->id;
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
        return trim($this->firstname.' '.$this->lastname);
    }

    public function oudere()
    {
        return $this->hasMany('App\Mantelzorger\Oudere', 'mantelzorger_id');
    }

    public function hulpverlener()
    {
        return $this->belongsTo('App\User', 'hulpverlener_id');
    }

    public function surveys()
    {
        return $this->hasMany('App\Questionnaire\Session', 'mantelzorger_id');
    }

    public function getDates()
    {
        return array_merge(parent::getDates(), array('birthday'));
    }
}
