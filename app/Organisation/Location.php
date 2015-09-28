<?php

namespace App\Organisation;

use App\System\Database\Eloquent\Model;
use App\System\Database\Eloquent\ValidationRules;
use App\System\Scopes\ModelAutoSort;
use Input;
use Validator;

class Location extends Model
{
    use ValidationRules, ModelAutoSort;

    protected $table = 'locations';

    protected static $rules = array(
        'name'            => 'required',
        'street'          => 'required',
        'postal'          => 'required',
        'city'            => 'required',
        'organisation_id' => 'required|exists:organisations,id'
    );

    public $autosort = 'name';

    protected $fillable = array(
        'name', 'street', 'postal', 'city', 'country', 'organisation_id'
    );

    public function organisation()
    {
        return $this->belongsTo('App\Organisation\Organisation');
    }
}