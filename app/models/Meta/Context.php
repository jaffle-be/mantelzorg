<?php

namespace Meta;


use System\Database\Eloquent\Model;

class Context extends Model
{

    const MANTELZORGER_RELATION = 'mantelzorger_relation_id';
    const OUDEREN_WOONSITUATIE = 'woonsituatie_id';
    const OORZAAK_HULPBEHOEFTE = 'oorzaak_hulpbehoefte_id';
    const BEL_PROFIEL = 'bel_profiel_id';

    protected $table = 'meta_contexts';

    protected $fillable = array('context');

    public function values()
    {
        return $this->hasMany('Meta\Value', 'context_id');
    }
}