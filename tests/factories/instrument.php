<?php

use App\Questionnaire\Questionnaire;

$factory(Questionnaire::class, 'survey', function(Faker\Generator $faker)
{
    return [
        'title' => $faker->sentence(),
        'active' => 0
    ];
});