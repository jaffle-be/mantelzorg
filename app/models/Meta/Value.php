<?php

namespace App\Meta;


use App\System\Database\Eloquent\Model;

class Value extends Model
{

    protected $table = 'meta_values';

    protected $fillable = array('context_id', 'value');

    public function context()
    {
        return $this->belongsTo('App\Meta\Context', 'context_id');
    }
}