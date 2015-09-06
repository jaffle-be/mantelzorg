<?php

class OudereSeeder extends Seeder
{

    public function run()
    {
        \App\Mantelzorger\Oudere::create(array(
            'firstname'       => 'Rudy',
            'lastname'        => 'Ruudski',
            'male'            => 1,
            'email'           => 'thomas.jaffle@gmail.com',
            'street'          => 'test',
            'postal'          => 'test',
            'city'            => 'test',
            'phone'           => 'test',
            'birthday'        => '1999-10-10',
            'mantelzorger_id' => 1
        ));
    }

} 