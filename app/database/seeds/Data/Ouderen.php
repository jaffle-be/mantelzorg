<?php namespace Data;


// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Mantelzorger\Mantelzorger;
use Mantelzorger\Oudere;

class Ouderen extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        $mantelzorgers = Mantelzorger::all();

        foreach ($mantelzorgers as $mantelzorger) {
            $this->addData($faker, $mantelzorger);
        }
    }

    public function addData($faker, $mantelzorger)
    {
        foreach (range(1, 5) as $index) {
            Oudere::create([
                'identifier'      => $faker->uuid,
                'email'           => $faker->unique()->email,
                'firstname'       => $faker->firstName,
                'lastname'        => $faker->lastName,
                'male'            => $faker->boolean(),
                'street'          => $faker->address,
                'postal'          => $faker->postcode,
                'city'            => $faker->city,
                'phone'           => $faker->phoneNumber,
                'mantelzorger_id' => $mantelzorger->id,
                'birthday'        => $faker->date()
            ]);
        }
    }

}