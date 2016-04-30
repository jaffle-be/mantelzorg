<?php

use App\Mantelzorger\Mantelzorger;
use App\Mantelzorger\Oudere;
use App\Meta\Context;
use App\Organisation\Organisation;
use App\Questionnaire\Choise;
use App\Questionnaire\Export\Report;
use App\Questionnaire\Panel;
use App\Questionnaire\Question;
use App\Questionnaire\Questionnaire;
use App\Questionnaire\Session;
use App\User;
use Faker\Generator;


$factory->defineAs(App\Organisation\Organisation::class, 'organisation', function (Faker\Generator $faker) {

    return [
        'name'       => $faker->username,
        'created_at' => $faker->dateTimeBetween('-12 months', '-2 months'),
        'updated_at' => $faker->dateTimeBetween('-2 months', 'now'),
    ];
});

$factory->defineAs(App\Organisation\Location::class, 'location', function (Faker\Generator $faker) {

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

$factory->defineAs(App\User::class, 'user', function (Faker\Generator $faker) {

    $location = factory('location')->create();

    return [
        'email' => $faker->unique()->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'male' => ($male = rand(0, 1)) ? 1 : 0,
        'firstname' => $male ? $faker->firstNameMale : $faker->firstNameFemale,
        'lastname' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'active' => true,
        'organisation_id' => $location->organisation_id,
        'organisation_location_id' => $location->id,
        'created_at' => $faker->dateTimeBetween('-12 months', '-2 months'),
        'updated_at' => $faker->dateTimeBetween('-2 months', 'now'),
    ];
});

$factory->defineAs(App\User::class, 'banned-user', function (Faker\Generator $faker) {

    $user = factory('user')->make();

    $user->active = false;

    return $user->toArray();
});

$factory->defineAs(App\User::class, 'admin', function (Faker\Generator $faker) {

    $user = Factory::build('user');

    $user->admin = true;

    return $user->toArray();
});

$factory->define(App\Beta\Registration::class, function (Faker\Generator $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->email,
        'organisation' => $faker->userName,
    ];
});

$factory->defineAs(Mantelzorger::class, 'mantelzorger', function (Faker\Generator $faker) {

    return [
        'identifier' => substr($faker->uuid, 0, 20),
        'email' => $faker->email,
        'firstname' => $faker->firstname,
        'lastname' => $faker->lastname,
        'male' => rand(0, 1),
        'street' => $faker->streetAddress,
        'city' => $faker->city,
        'postal' => substr($faker->postcode, 0, 5),
        'phone' => $faker->phoneNumber,
        'birthday' => $faker->dateTimeBetween('-60 years', '-23 years'),
        //by default set mantelzorgers to be mantelzorgers of the user
        'hulpverlener_id' => 1,
        'created_at' => $faker->dateTimeBetween('-12 months', '-2 months')->format('Y-m-d H:i:s'),
        'updated_at' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
    ];
});


$factory->defineAs(Oudere::class, 'oudere', function (Faker\Generator $faker) {

    $relations = Context::where('context', Context::MANTELZORGER_RELATION)->first()->values->pluck('id')->all();
    $woonsituaties = Context::where('context', Context::OUDEREN_WOONSITUATIE)->first()->values->pluck('id')->all();
    $oorzaken = Context::where('context', Context::OORZAAK_HULPBEHOEFTE)->first()->values->pluck('id')->all();
    $belprofielen = Context::where('context', Context::BEL_PROFIEL)->first()->values->pluck('id')->all();

    return [
        'identifier' => substr($faker->uuid, 0, 20),
        'email' => $faker->email,
        'firstname' => $faker->firstname,
        'lastname' => $faker->lastname,
        'male' => rand(0, 1),
        'street' => $faker->streetAddress,
        'city' => $faker->city,
        'postal' => substr($faker->postcode, 0, 5),
        'phone' => $faker->phoneNumber,
        'birthday' => $faker->dateTimeBetween('-100 years', '-40 years'),
        //by default set mantelzorgers to be mantelzorgers of the user
        'mantelzorger_id' => 1,

        'mantelzorger_relation_id' => $relations[array_rand($relations, 1)],

        'woonsituatie_id' => $woonsituaties[array_rand($woonsituaties, 1)],
        'oorzaak_hulpbehoefte_id' => $oorzaken[array_rand($oorzaken, 1)],
        'bel_profiel_id' => $belprofielen[array_rand($belprofielen, 1)],
        'details_diagnose' => $faker->paragraph(5),

        'created_at' => $faker->dateTimeBetween('-12 months', '-2 months')->format('Y-m-d H:i:s'),
        'updated_at' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
    ];

});


$factory->defineAs(Questionnaire::class, 'survey', function (Faker\Generator $faker) {
    return [
        'title' => substr($faker->sentence(), 0, 30),
        'active' => 0
    ];
});

$factory->defineAs(Panel::class, 'panel', function (Faker\Generator $faker) use ($factory) {

    $weight = Panel::count() + 1;

    $colors = ['purple', 'blue', 'red', 'orange', 'yellow', 'green', 'gray'];

    return [
        'questionnaire_id' => 1,
        'panel_weight' => $weight,
        'color' => $colors[array_rand($colors, 1)],
        'title' => $faker->sentence(),
    ];
});

$factory->defineAs(Question::class, 'question', function (Generator $faker) use ($factory) {

    return [
        'questionnaire_id' => 1,
        'questionnaire_panel_id' => 1,
        'title' => $faker->sentence(),
        'question' => $faker->paragraph(),
        'meta' => $faker->text(),
        'sort' => Question::count() + 1,
    ];
});

$factory->defineAs(Question::class, 'mc-question', function (Generator $faker) use ($factory) {
    $attributes = factory('question')->raw();

    return array_merge($attributes, [
        'multiple_choise' => true,
    ]);
});

$factory->defineAs(Question::class, 'mcma-question', function (Generator $faker) use ($factory) {
    $attributes = factory('question')->raw();

    return array_merge($attributes, [
        'multiple_choise' => true,
        'multiple_answer' => true
    ]);
});

$factory->defineAs(Question::class, 'summary-question', function (Generator $faker) use ($factory) {
    $attributes = factory('question')->raw();

    return array_merge($attributes, [
        'multiple_choise' => true,
        'summary_question' => true,
    ]);
});

$factory->defineAs(Question::class, 'explainable-question', function (Generator $faker) use ($factory) {
    $attributes = factory('question')->raw();

    return array_merge($attributes, [
        'explainable' => true,
    ]);
});

$factory->defineAs(Choise::class, 'choise', function (Generator $faker) {
    return [
        'question_id' => 1,
        'title' => $faker->sentence(),
        'sort_weight' => Choise::count() + 1,
    ];
});

$factory->defineAs(Session::class, 'session', function (Generator $faker) {
    return [
        'user_id' => 1,
        'oudere_id' => 1,
        'mantelzorger_id' => 1
    ];
});

$factory->defineAs(Report::class, 'report', function (Generator $faker) use ($factory) {
    $survey = Questionnaire::first();

    if (! $survey) {
        $survey = $factory->create('survey');
    }

    return [
        'filename' => $survey->title.'.xlsx',
        'survey_count' => rand(15, 900),
        'questionnaire_id' => $survey->id,
    ];
});

$factory->defineAs(Report::class, 'user-report', function (Generator $faker) use ($factory) {
    $report = factory('report')->raw();

    return array_merge($report, ['user_id' => User::first()->id]);
});

$factory->defineAs(Report::class, 'organisation-report', function (Generator $faker) use ($factory) {
    $report = factory('report')->raw();

    return array_merge($report, ['organisation_id' => Organisation::first()->id]);
});
