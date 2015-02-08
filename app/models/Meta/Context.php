<?php

namespace Meta;

use Eloquent;

class Context extends Eloquent
{

    const MANTELZORGER_RELATION = 'mantelzorger_relation';
    const OUDEREN_WOONSITUATIE = 'ouderen_woonsituatie';
    const OORZAAK_HULPBEHOEFTE = 'oorzaak_hulpbehoefte';

    protected $table = 'meta_contexts';

    protected $fillable = array('context');

    public function values()
    {
        return $this->hasMany('Meta\Value', 'context_id');
    }
}