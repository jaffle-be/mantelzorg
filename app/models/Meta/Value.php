<?php

namespace Meta;


use System\Database\Eloquent\Model;

class Value extends Model
{

    protected $table = 'meta_values';

    protected $fillable = array('context_id', 'value');

    public function context()
    {
        return $this->belongsTo('Meta\Context', 'context_id');
    }
}