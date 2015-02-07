<?php

namespace Meta;

use Eloquent;

class Context extends Eloquent
{

    const RELATION_MANTELZORGER_OUDERE = 'relation_mantelzorger_oudere';
    const OUDEREN_WOONSITUATIE = 'ouderen_woonsituatie';

    protected $table = 'meta_contexts';

    protected $fillable = array('context');

    public function values()
    {
        return $this->hasMany('Meta\Value', 'context_id');
    }
}