<?php

class QuestionSeeder extends Seeder{

    public function run()
    {
        \Questionnaire\Question::create(array(
            'questionnaire_id' => 1,
            'questionnaire_panel_id' => 1,
            'title' => 'Zorgverlening',
            'question' => 'Ik zou graag een beeld krijgen van de zorgsituatie en hoe u als mantelzorger zich hierbij voelt. Welke taken neemt u op in de zorg? Waarbij helpt u zoal?',
            'multiple_choise' => 1,
            'multiple_answer' => 1
        ));

        \Questionnaire\Question::create(array(
            'questionnaire_id' => 1,
            'questionnaire_panel_id' => 1,
            'title' => 'Intensiteit',
            'question' => 'Een vraagje over intensiteit'
        ));
    }

} 