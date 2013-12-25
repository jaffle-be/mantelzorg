<?php

class ChoiseSeeder extends Seeder{

    public function run()
    {
        $this->panel1();
    }

    protected function panel1()
    {
        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title' => 'Boodschappen',
            'sort_weight' => 0
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title' => 'Maaltijden bereiden',
            'sort_weight' => 10
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title' => 'Poetsen',
            'sort_weight' => 20
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title' => 'Kledij wassen / strijken',
            'sort_weight' => 30
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title' => 'Persoonsverzorging',
            'sort_weight' => 40
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title' => 'Transfers',
            'sort_weight' => 50
        ));
    }

} 