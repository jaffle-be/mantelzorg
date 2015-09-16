<?php
namespace App\Organisation;

use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;
use Input;
use Validator;

class Organisation extends Model
{
    use ValidationRules;

    protected $table = 'organisations';

    protected $fillable = array(
        'name'
    );

    protected static $rules = array(
        'name' => 'required|unique:organisations'
    );

    public function locations()
    {
        return $this->hasMany('App\Organisation\Location');
    }
}