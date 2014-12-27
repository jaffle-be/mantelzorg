<?php namespace Data;

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Hash;
use Illuminate\Database\Seeder;
use User;

class Users extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 15) as $index)
        {
            User::create([
                'email'     => $faker->unique()->email,
                'password'  => Hash::make($faker->sha1),
                'firstname' => $faker->firstName,
                'lastname'  => $faker->lastName,
                'male'      => $faker->boolean(),
                'phone'     => $faker->phoneNumber,
                'active'    => $faker->boolean(),
                ''
            ]);
        }
    }

}