<?php

class QuestionnaireSeeder extends \Illuminate\Database\Seeder{

    public function run()
    {
        \Questionnaire\Questionnaire::create(array(
            'title' => 'Instrument',
            'active' => 1
        ));
    }

}