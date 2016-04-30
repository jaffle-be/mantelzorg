<?php

use Laracasts\TestDummy\Factory;

$factory(App\User::class, 'user', function (Faker\Generator $faker) {

    $location = factory('location')->create();

    return [
        'email'                    => $faker->unique()->email,
        'password'                 => bcrypt(str_random(10)),
        'remember_token'           => str_random(10),
        'male'                     => ($male = rand(0, 1)) ? 1 : 0,
        'firstname'                => $male ? $faker->firstNameMale : $faker->firstNameFemale,
        'lastname'                 => $faker->lastName,
        'phone'                    => $faker->phoneNumber,
        'active'                   => true,
        'organisation_id'          => $location->organisation_id,
        'organisation_location_id' => $location->id,
        'created_at'               => $faker->dateTimeBetween('-12 months', '-2 months'),
        'updated_at'               => $faker->dateTimeBetween('-2 months', 'now'),
    ];
});

$factory(App\User::class, 'banned-user', function (Faker\Generator $faker) {

    $user = factory('user')->make();

    $user->active = false;

    return $user->toArray();
});

$factory(App\User::class, 'admin', function (Faker\Generator $faker) {

    $user = Factory::build('user');

    $user->admin = true;

    return $user->toArray();
});

$factory(App\Beta\Registration::class, function (Faker\Generator $faker) {
    return [
        'firstname'    => $faker->firstName,
        'lastname'     => $faker->lastName,
        'email'        => $faker->unique()->email,
        'organisation' => $faker->userName,
    ];
});
