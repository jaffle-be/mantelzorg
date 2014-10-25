<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

    protected static $rules = array(
        'email' => 'required|email|unique:users',
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
        'email', 'firstname', 'lastname', 'male', 'admin', 'active', 'password', 'phone', 'organisation_id', 'organisation_location_id'
    );


	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

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
     * @param  string $value
     *
     * @return void
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


    public function validator($fields = null, $input = null)
    {
        $rules = static::$rules;

        if(!empty($fields))
        {
            if(!is_array($fields)) $fields = array($fields);

            $rules = array_intersect_key($rules, array_flip($fields));
        }

        if(empty($input))
        {
            $input = Input::all();
        }

        return Validator::make($input, $rules);
    }

    public function generateNewPassword($length = 8, $strength = 4)
    {
        $vowels = 'aeiouy';
        $consonants = 'bcdfghjklmnpqrstvwxz';
        if ($strength & 1) {
            $consonants .= 'BCDFGHJKLMNPQRSTVWXZ';
        }
        if ($strength & 2) {
            $vowels .= "AEIOUY";
        }
        if ($strength & 4) {
            $consonants .= '23456789';
        }
        if ($strength & 8) {
            $consonants .= '@#$%';
        }

        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
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
        return $this->belongsTo('Organisation\\Organisation', 'organisation_id');
    }

    public function mantelzorgers()
    {
        return $this->hasMany('Mantelzorger\\Mantelzorger', 'hulpverlener_id');
    }

    public function surveys()
    {
        return $this->hasMany('Questionnaire\\Session', 'user_id');
    }

}