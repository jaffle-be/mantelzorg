<?php

use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;
use App\Meta\Context;

$factory(Mantelzorger::class, 'mantelzorger', function (Faker\Generator $faker) {

    return [
        'identifier'      => substr($faker->uuid, 0, 20),
        'email'           => $faker->email,
        'firstname'       => $faker->firstname,
        'lastname'        => $faker->lastname,
        'male'            => rand(0, 1),
        'street'          => $faker->streetAddress,
        'city'            => $faker->city,
        'postal'          => substr($faker->postcode, 0, 5),
        'phone'           => $faker->phoneNumber,
        'birthday'        => $faker->dateTimeBetween('-60 years', '-23 years'),
        //by default set mantelzorgers to be mantelzorgers of the user
        'hulpverlener_id' => 1,
        'created_at'      => $faker->dateTimeBetween('-12 months', '-2 months')->format('Y-m-d H:i:s'),
        'updated_at'      => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
    ];
});


$factory(Oudere::class, 'oudere', function (Faker\Generator $faker) {

    $relations = Context::where('context', Context::MANTELZORGER_RELATION)->first()->values->lists('id')->all();
    $woonsituaties = Context::where('context', Context::OUDEREN_WOONSITUATIE)->first()->values->lists('id')->all();
    $oorzaken = Context::where('context', Context::OORZAAK_HULPBEHOEFTE)->first()->values->lists('id')->all();
    $belprofielen = Context::where('context', Context::BEL_PROFIEL)->first()->values->lists('id')->all();

    return [
        'identifier'      => substr($faker->uuid, 0, 20),
        'email'           => $faker->email,
        'firstname'       => $faker->firstname,
        'lastname'        => $faker->lastname,
        'male'            => rand(0, 1),
        'street'          => $faker->streetAddress,
        'city'            => $faker->city,
        'postal'          => substr($faker->postcode, 0, 5),
        'phone'           => $faker->phoneNumber,
        'birthday'        => $faker->dateTimeBetween('-100 years', '-40 years'),
        //by default set mantelzorgers to be mantelzorgers of the user
        'mantelzorger_id' => 1,

        'mantelzorger_relation_id' => $relations[array_rand($relations, 1)],

        'woonsituatie_id' => $woonsituaties[array_rand($woonsituaties, 1)],
        'oorzaak_hulpbehoefte_id' => $oorzaken[array_rand($oorzaken, 1)],
        'bel_profiel_id' => $belprofielen[array_rand($belprofielen, 1)],
        'details_diagnose' => $faker->paragraph(5),

        'created_at'      => $faker->dateTimeBetween('-12 months', '-2 months')->format('Y-m-d H:i:s'),
        'updated_at'      => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
    ];

});