<?php

namespace App\Organisation;

use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;
use App\System\Scopes\ModelAutoSort;

class Organisation extends Model
{
    use ValidationRules, ModelAutoSort;

    protected $table = 'organisations';

    protected $fillable = array(
        'name',
    );

    protected static $rules = array(
        'name' => 'required|unique:organisations',
    );

    public $autosort = 'name';

    public function locations()
    {
        return $this->hasMany('App\Organisation\Location');
    }
}
