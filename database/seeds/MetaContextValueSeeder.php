<?php

class MetaContextValueSeeder extends \Illuminate\Database\Seeder
{

    public function run()
    {
        App\Meta\Context::create(array(
            'context' => 'mantelzorger_relation',
        ));

        App\Meta\Value::create(array(
            'context_id' => 1,
            'value'      => 'partner',
        ));

        App\Meta\Value::create(array(
            'context_id' => 1,
            'value'      => 'kind',
        ));

        App\Meta\Value::create(array(
            'context_id' => 1,
            'value'      => 'vriend',
        ));

        App\Meta\Value::create(array(
            'context_id' => 1,
            'value'      => 'buur',
        ));
    }

} 