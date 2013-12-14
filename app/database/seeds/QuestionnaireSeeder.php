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
            'panel_weight' => 0
        ));

        \Questionnaire\Question::create(array(
            'questionnaire_id' => 1,
            'questionnaire_panel_id' => 1,
            'title' => 'Zorgverlening',
            'question' => 'Ik zou graag een beeld krijgen van de zorgsituatie en hoe u als mantelzorger zich hierbij voelt. Welke taken neemt u op in de zorg? Waarbij helpt u zoal?'
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
            'panel_weight' => 10
        ));

        \Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title' => 'Relaties',
            'panel_weight' => 20
        ));

        \Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title' => 'Omgeving',
            'panel_weight' => 30
        ));

        \Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title' => 'Overzicht',
            'panel_weight' => 40
        ));
    }

}