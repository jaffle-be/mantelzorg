<?php

class PanelSeeder extends Seeder{

    public function run()
    {
        \Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title' => 'Zorgverlening',
            'panel_weight' => 0,
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