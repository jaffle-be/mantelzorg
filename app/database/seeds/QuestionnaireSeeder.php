<?php

class QuestionnaireSeeder extends \Illuminate\Database\Seeder{

    public function run()
    {
        \Questionnaire\Questionnaire::create(array(
            'title' => 'Instrument'
        ));

        \Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title' => 'Zorgverlening',
            'panel_weight' => 0,
        ));

        \Questionnaire\Question::create(array(
            'questionnaire_id' => 1,
            'questionnaire_panel_id' => 1,
            'title' => 'Zorgverlening',
            'question' => 'Ik zou graag een beeld krijgen van de zorgsituatie en hoe u als mantelzorger zich hierbij voelt. Welke taken neemt u op in de zorg? Waarbij helpt u zoal?',
            'multiple_choise' => 1,
            'multiple_answer' => 1
        ));

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

        \Questionnaire\Question::create(array(
            'questionnaire_id' => 1,
            'questionnaire_panel_id' => 1,
            'title' => 'Intensiteit',
            'question' => 'Een vraagje over intensiteit'
        ));

        \Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title' => 'Mantelzorger',
            'panel_weight' => 10,
            'color' => 'green',
        ));

        \Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title' => 'Relaties',
            'panel_weight' => 20,
            'color' => 'red'
        ));

        \Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title' => 'Omgeving',
            'panel_weight' => 30,
            'color' => 'orange'
        ));

        \Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title' => 'Overzicht',
            'panel_weight' => 40,
            'color' => 'blue',
        ));
    }

}