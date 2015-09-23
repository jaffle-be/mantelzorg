<?php

use Illuminate\Database\Seeder;

class MantelzorgerSeeder extends Seeder
{

    public function run()
    {
        \App\Mantelzorger\Mantelzorger::create(array(
            'firstname'       => 'Ruud',
            'lastname'        => 'vdh',
            'male'            => 1,
            'email'           => 'thomas@jaffle.be',
            'street'          => 'test',
            'postal'          => 'test',
            'city'            => 'test',
            'phone'           => 'test',
            'birthday'        => '1999-10-10',
            'hulpverlener_id' => 1,
        ));
    }
}