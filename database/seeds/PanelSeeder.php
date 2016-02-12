<?php

use Illuminate\Database\Seeder;

class PanelSeeder extends Seeder
{

    public function run()
    {
        \App\Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title'            => 'A. Zorgverlening',
            'panel_weight'     => 0,
        ));

        \App\Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title'            => 'B. Mantelzorger',
            'panel_weight'     => 10,
            'color'            => 'green',
        ));

        \App\Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title'            => 'C. Relaties',
            'panel_weight'     => 20,
            'color'            => 'red'
        ));

        \App\Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title'            => 'D. Omgeving',
            'panel_weight'     => 30,
            'color'            => 'orange'
        ));

        \App\Questionnaire\Panel::create(array(
            'questionnaire_id' => 1,
            'title'            => 'E. Overzicht',
            'panel_weight'     => 40,
            'color'            => 'blue',
        ));
    }

} 