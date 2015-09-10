<?php


$factory(App\Organisation\Organisation::class, 'organisation', function (Faker\Generator $faker) {

    return [
        'name'       => $faker->username,
        'created_at' => $faker->dateTimeBetween('-12 months', '-2 months'),
        'updated_at' => $faker->dateTimeBetween('-2 months', 'now'),
    ];
});

$factory(App\Organisation\Location::class, 'location', function (Faker\Generator $faker) {

    return [
        'organisation_id' => 'factory:organisation',
        'name'            => $faker->username,
        'street'          => $faker->streetName . ' ' . rand(1, 100),
        'city'            => $faker->city,
        'postal'          => $faker->postcode,
        'country'         => $faker->countryCode,
        'created_at'      => $faker->dateTimeBetween('-12 months', '-2 months'),
        'updated_at'      => $faker->dateTimeBetween('-2 months', 'now'),
    ];
});