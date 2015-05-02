<?php

namespace Meta;


use System\Database\Eloquent\Model;

class Context extends Model
{

    const MANTELZORGER_RELATION = 'mantelzorger_relation';
    const OUDEREN_WOONSITUATIE = 'ouderen_woonsituatie';
    const OORZAAK_HULPBEHOEFTE = 'oorzaak_hulpbehoefte';
    const BEL_PROFIEL = 'bel_profiel';

    protected $table = 'meta_contexts';

    protected $fillable = array('context');

    public function values()
    {
        return $this->hasMany('Meta\Value', 'context_id');
    }
}