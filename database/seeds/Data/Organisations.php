<?php namespace Data;

use App\Organisation\Organisation;
use App\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class Organisations extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        $organisations = ['CM Westvlaanderen', 'CM Oostvlaanderen', 'Bond van iets', 'Bond van iets anders', 'Wit-Geel Kruis', 'Rode Kruis', 'Seniors first', 'Kangoroo'];

        foreach($organisations as $organisation)
        {
            Organisation::create([
                'name' => $organisation
            ]);
        }


        $organisations = Organisation::all();

        foreach(User::all() as $user)
        {
            $user->organisation()->associate($organisations->random());

            $user->save();
        }
    }

}