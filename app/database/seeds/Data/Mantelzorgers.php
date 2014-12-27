<?php namespace Data;

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Mantelzorger\Mantelzorger;
use User;

class Mantelzorgers extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        $users = User::all();

        foreach ($users as $user)
        {
            $this->addData($user, $faker);
        }
    }

    private function addData($user, $faker)
    {
        foreach (range(1, 10) as $index)
        {
            Mantelzorger::create([
                'identifier'      => $faker->uuid,
                'email'           => $faker->unique()->email,
                'firstname'       => $faker->firstName,
                'lastname'        => $faker->lastName,
                'male'            => $faker->boolean(),
                'street'          => $faker->address,
                'postal'          => $faker->postcode,
                'city'            => $faker->city,
                'phone'           => $faker->phoneNumber,
                'hulpverlener_id' => $user->id,
                'birthday'        => $faker->date()
            ]);
        }
    }

}