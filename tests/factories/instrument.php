<?php

use App\Questionnaire\Choise;
use App\Questionnaire\Panel;
use App\Questionnaire\Question;
use App\Questionnaire\Questionnaire;
use App\Questionnaire\Session;
use Faker\Generator;
use Laracasts\TestDummy\Factory;

$factory(Questionnaire::class, 'survey', function (Faker\Generator $faker) {
    return [
        'title'  => substr($faker->sentence(), 0, 30),
        'active' => 0
    ];
});

$factory(Panel::class, 'panel', function (Faker\Generator $faker) use ($factory) {

    $weight = Panel::count() + 1;

    $colors = ['purple', 'blue', 'red', 'orange', 'yellow', 'green', 'gray'];

    return [
        'questionnaire_id' => 1,
        'panel_weight'     => $weight,
        'color'            => $colors[array_rand($colors, 1)],
        'title'            => $faker->sentence(),
    ];
});

$factory(Question::class, 'question', function (Generator $faker) use ($factory) {

    return [
        'questionnaire_id'       => 1,
        'questionnaire_panel_id' => 1,
        'title'                  => $faker->sentence(),
        'question'               => $faker->paragraph(),
        'meta'                   => $faker->text(),
        'sort'                   => Question::count() + 1,
    ];
});

$factory(Question::class, 'mc-question', function (Generator $faker) use ($factory) {
    $attributes = Factory::attributesFor('question');

    return array_merge($attributes, [
        'multiple_choise' => true,
    ]);
});

$factory(Question::class, 'mcma-question', function (Generator $faker) use ($factory) {
    $attributes = Factory::attributesFor('question');

    return array_merge($attributes, [
        'multiple_choise' => true,
        'multiple_answer' => true
    ]);
});

$factory(Question::class, 'summary-question', function (Generator $faker) use ($factory) {
    $attributes = Factory::attributesFor('question');

    return array_merge($attributes, [
        'multiple_choise'  => true,
        'summary_question' => true,
    ]);
});

$factory(Question::class, 'explainable-question', function (Generator $faker) use ($factory) {
    $attributes = Factory::attributesFor('question');

    return array_merge($attributes, [
        'explainable' => true,
    ]);
});

$factory(Choise::class, 'choise', function (Generator $faker) {
    return [
        'question_id' => 1,
        'title'       => $faker->sentence(),
        'sort_weight' => Choise::count() + 1,
    ];
});

$factory(Session::class, 'session', function (Generator $faker) {
    return [
        'user_id'         => 1,
        'oudere_id'       => 1,
        'mantelzorger_id' => 1
    ];
});