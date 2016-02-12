<?php

use App\Meta\Value;

class MetaContextValueSeeder extends \Illuminate\Database\Seeder
{

    public function run()
    {
        $context = App\Meta\Context::create(array(
            'context' => 'mantelzorger_relation_id',
        ));

        $context->values()->save(new Value([
            'context_id' => 1,
            'value'      => 'partner',
        ]));
        $context->values()->save(new Value([
            'context_id' => 1,
            'value'      => 'kind',
        ]));
        $context->values()->save(new Value([
            'context_id' => 1,
            'value'      => 'vriend',
        ]));
        $context->values()->save(new Value([
            'context_id' => 1,
            'value'      => 'buur',
        ]));
    }

}