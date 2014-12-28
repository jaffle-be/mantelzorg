<?php

class UserTableSeeder extends \Illuminate\Database\Seeder
{

    public function run()
    {
        //no point in trying to hack me using this info. This is just more convenient that changing code to insert my record.
        //I always change my pass right after i seeded in the application itself.
        User::create(array(
            'firstname' => 'Thomas',
            'lastname'  => 'Warlop',
            'email'     => 'thomas@jaffle.be',
            'password'  => Hash::make('thomas'),
            'admin'     => '1',
            'active'    => '1',
            'male'      => '1'
        ));
    }

} 