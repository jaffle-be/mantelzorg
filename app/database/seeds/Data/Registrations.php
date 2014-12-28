<?php namespace Data;

// Composer: "fzaninotto/faker": "v1.3.0"
use Beta\Registration;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class Registrations extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Registration::create([
                'email'        => $faker->unique()->email,
                'firstname'    => $faker->firstName,
                'lastname'     => $faker->lastName,
                'organisation' => $faker->name
            ]);
        }
    }

}