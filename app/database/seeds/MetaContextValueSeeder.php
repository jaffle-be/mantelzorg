<?php

class MetaContextValueSeeder extends \Illuminate\Database\Seeder
{

    public function run()
    {
        Meta\Context::create(array(
            'context' => 'relation_mantelzorger_oudere',
        ));

        Meta\Value::create(array(
            'context_id' => 1,
            'value' => 'partner',
        ));

        Meta\Value::create(array(
            'context_id' => 1,
            'value' => 'kind',
        ));

        Meta\Value::create(array(
            'context_id' => 1,
            'value' => 'vriend',
        ));

        Meta\Value::create(array(
            'context_id' => 1,
            'value' => 'buur',
        ));
    }

} 