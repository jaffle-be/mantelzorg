<?php

namespace App;

use App\Questionnaire\Export\Exportable;
use App\Search\Model\Searchable;
use App\Search\Model\SearchableTrait;
use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, Searchable, Exportable, AuthorizableContract
{
    use Authenticatable, CanResetPassword, SearchableTrait;
    use ValidationRules;
    use Authorizable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    protected static $searchableMapping = [
        'male' => [
            'type' => 'boolean',
        ],
        'admin' => [
            'type' => 'boolean',
        ],
        'active' => [
            'type' => 'boolean',
        ],
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

    protected static $rules = array(
        'email' => 'required|email|unique:users,id,#user',
        'firstname' => 'required',
        'lastname' => 'required',
        'male' => 'required|',
        'admin' => 'in:0,1',
        'active' => 'in:0,1',
        'password' => 'required',
        'organisation_id' => 'required|exists:organisations,id',
        'organisation_location_id' => 'required|exists:locations,id',
    );

    protected $fillable = array(
        'email', 'firstname', 'lastname', 'male', 'admin', 'active', 'password', 'phone', 'organisation_id', 'organisation_location_id',
    );

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * @return array
     */
    public function toExportArray()
    {
        return [
            'hulpverlener-id' => isset($this->attributes['id']) ? $this->attributes['id'] : null,
            'hulpverlener-email' => isset($this->attributes['email']) ? $this->attributes['email'] : null,
            'hulpverlener-firstname' => isset($this->attributes['firstname']) ? $this->attributes['firstname'] : null,
            'hulpverlener-lastname' => isset($this->attributes['lastname']) ? $this->attributes['lastname'] : null,
            'hulpverlener-male' => isset($this->attributes['male']) ? $this->attributes['male'] : null,
            'hulpverlener-phone' => !empty($this->attributes['phone']) ? $this->attributes['phone'] : '',
            'hulpverlener-organisation' => $this->organisation ? $this->organisation->name : null,
            'hulpverlener-organisation_location' => $this->organisation_location ? $this->organisation_location->name : null,
        ];
    }

    public function getFullnameAttribute()
    {
        if (isset($this->attributes['fullname'])) {
            return $this->attributes['fullname'];
        }

        return trim($this->attributes['firstname'].' '.$this->attributes['lastname']);
    }

    public function generateNewPassword($length = 8, $strength = 4)
    {
        $vowels = 'aeiouy';
        $consonants = 'bcdfghjklmnpqrstvwxz';
        if ($strength & 1) {
            $consonants .= 'BCDFGHJKLMNPQRSTVWXZ';
        }
        if ($strength & 2) {
            $vowels .= 'AEIOUY';
        }
        if ($strength & 4) {
            $consonants .= '23456789';
        }
        if ($strength & 8) {
            $consonants .= '@#$%';
        }

        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; ++$i) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }

        return $password;
    }

    public function organisation()
    {
        return $this->belongsTo('App\Organisation\Organisation', 'organisation_id');
    }

    public function mantelzorgers()
    {
        return $this->hasMany('App\Mantelzorger\Mantelzorger', 'hulpverlener_id');
    }

    public function organisation_location()
    {
        return $this->belongsTo('App\Organisation\Location', 'organisation_location_id');
    }

    public function surveys()
    {
        return $this->hasMany('App\Questionnaire\Session', 'user_id');
    }
}
