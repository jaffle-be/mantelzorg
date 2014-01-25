<?php

namespace Meta;

use Eloquent;

class Value extends Eloquent
{

    protected $table = 'meta_values';

    protected $fillable = array('context_id', 'value');

    public function context()
    {
        return $this->belongsTo('Meta\Context', 'context_id');
    }

} 