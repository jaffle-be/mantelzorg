<?php

class MetaContextValueSeeder extends \Illuminate\Database\Seeder
{

    public function run()
    {
        Meta\Context::create(array(
            'context' => 'mantelzorger_relation',
        ));

        Meta\Value::create(array(
            'context_id' => 1,
            'value'      => 'partner',
        ));

        Meta\Value::create(array(
            'context_id' => 1,
            'value'      => 'kind',
        ));

        Meta\Value::create(array(
            'context_id' => 1,
            'value'      => 'vriend',
        ));

        Meta\Value::create(array(
            'context_id' => 1,
            'value'      => 'buur',
        ));
    }

} 