<?php

class ChoiseSeeder extends Seeder
{

    public function run()
    {
        $this->panel1();
        $this->panel2();
        $this->panel3();
        $this->panel4();
        $this->panel5();
    }

    protected function panel1()
    {
        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Boodschappen',
            'sort_weight' => 0
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Maaltijden bereiden',
            'sort_weight' => 10
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Poetsen',
            'sort_weight' => 20
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Kledij wassen / strijken',
            'sort_weight' => 30
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Persoonsverzorging',
            'sort_weight' => 40
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Transfers',
            'sort_weight' => 50
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Socio-emotionele steun',
            'sort_weight' => 60
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Vervoer, begeleiding uitstappen',
            'sort_weight' => 70
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Administratie',
            'sort_weight' => 80
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Organiseren professionele zorg',
            'sort_weight' => 90
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Hulp bij medicatiegebruik',
            'sort_weight' => 100
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Toezicht houden, aanwezig zijn',
            'sort_weight' => 110
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 1,
            'title'       => 'Andere',
            'sort_weight' => 120
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 2,
            'title'       => '24/24',
            'sort_weight' => 0
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 2,
            'title'       => 'Dagelijks minder dan een uur',
            'sort_weight' => 10
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 2,
            'title'       => 'Dagelijks meer dan een uur',
            'sort_weight' => 20
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 2,
            'title'       => 'Wekelijks minder dan 3 uur',
            'sort_weight' => 30
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 2,
            'title'       => 'Wekelijks meer dan 3 uur',
            'sort_weight' => 40
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 2,
            'title'       => 'andere',
            'sort_weight' => 50
        ));

        $this->yesNo(4);
        $this->yesNo(5);

        $this->threeChoises(6);
        $this->threeChoises(7);
        $this->threeChoises(8);

        $this->fiveChoises(12);
    }

    protected function panel2()
    {
        \Questionnaire\Choise::create(array(
            'question_id' => 13,
            'title'       => 'Betaald werk',
            'sort_weight' => 0
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 13,
            'title'       => '(Klein) kinderen',
            'sort_weight' => 10
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 13,
            'title'       => 'Huishouden',
            'sort_weight' => 20
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 13,
            'title'       => 'Mantelzorg voor andere personen',
            'sort_weight' => 30
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 13,
            'title'       => 'Vrijwilligerswerk',
            'sort_weight' => 40
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 13,
            'title'       => "Vrije tijd of hobby's",
            'sort_weight' => 50
        ));

        $this->yesNo(14);

        \Questionnaire\Choise::create(array(
            'question_id' => 15,
            'title'       => "Vermoeidheid of slaapproblemen",
            'sort_weight' => 0
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 15,
            'title'       => "Veranderingen in gedrag",
            'sort_weight' => 10
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 15,
            'title'       => "Psychische klachten",
            'sort_weight' => 20
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 15,
            'title'       => "Lichamelijke klachten",
            'sort_weight' => 30
        ));

        $this->threeChoises(16);
        $this->threeChoises(17);
        $this->threeChoises(18);

        $this->yesNo(19);
        $this->yesNo(20);

        $this->fiveChoises(24);
    }

    protected function panel3()
    {
        \Questionnaire\Choise::create(array(
            'question_id' => 25,
            'title'       => "IADL- / ADL-problemen",
            'sort_weight' => 0
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 25,
            'title'       => "Gedragsstoornissen",
            'sort_weight' => 10
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 25,
            'title'       => "Karakterveranderingen",
            'sort_weight' => 20
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 25,
            'title'       => "Cognitieve achteruitgang",
            'sort_weight' => 30
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => 25,
            'title'       => "Stemmingstoornissen",
            'sort_weight' => 40
        ));

        $this->yesNo(26);
        $this->yesNo(27);
        $this->yesNo(28);
        $this->threeChoises(29);
        $this->threeChoises(30);
        $this->threeChoises(31);
        $this->yesNo(32);
        $this->threeChoises(33);
        $this->threeChoises(34);
        $this->fiveChoises(38);
    }

    public function panel4()
    {
        $this->yesNo(39);
        $this->yesNo(40);
        $this->yesNo(41);
        $this->yesNo(42);
        $this->yesNo(43);
        \Questionnaire\Choise::create(array(
            'question_id' => 44,
            'title'       => "Mobiliteit",
            'sort_weight' => 0
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 44,
            'title'       => "Eetsituatie",
            'sort_weight' => 10
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 44,
            'title'       => "Verzorging",
            'sort_weight' => 20
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 44,
            'title'       => "Communicatie",
            'sort_weight' => 30
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 44,
            'title'       => "Veiligheid",
            'sort_weight' => 40
        ));
        $this->yesNo(45);
        $this->yesNo(46);
        $this->yesNo(47);
        $this->fiveChoises(51);
    }

    public function panel5()
    {
        \Questionnaire\Choise::create(array(
            'question_id' => 52,
            'title'       => "zeer zwaar",
            'sort_weight' => 0
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 52,
            'title'       => "zwaar",
            'sort_weight' => 10
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 52,
            'title'       => "matig zwaar",
            'sort_weight' => 20
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 52,
            'title'       => "niet zwaar",
            'sort_weight' => 30
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => 52,
            'title'       => "helemaal niet zwaar",
            'sort_weight' => 40
        ));

    }

    protected function yesNo($questionid)
    {
        \Questionnaire\Choise::create(array(
            'question_id' => $questionid,
            'title'       => 'ja',
            'sort_weight' => 0
        ));

        \Questionnaire\Choise::create(array(
            'question_id' => $questionid,
            'title'       => 'nee',
            'sort_weight' => 10
        ));
    }

    protected function threeChoises($questionid)
    {
        \Questionnaire\Choise::create(array(
            'question_id' => $questionid,
            'title'       => 'laag',
            'sort_weight' => 0
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => $questionid,
            'title'       => 'midden',
            'sort_weight' => 10
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => $questionid,
            'title'       => 'hoog',
            'sort_weight' => 20
        ));
    }

    protected function fiveChoises($questionid)
    {
        \Questionnaire\Choise::create(array(
            'question_id' => $questionid,
            'title'       => 'zeer ontevreden',
            'sort_weight' => 0
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => $questionid,
            'title'       => 'ontevreden',
            'sort_weight' => 10
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => $questionid,
            'title'       => 'matig tevreden',
            'sort_weight' => 20
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => $questionid,
            'title'       => 'tevreden',
            'sort_weight' => 30
        ));
        \Questionnaire\Choise::create(array(
            'question_id' => $questionid,
            'title'       => 'zeer tevreden',
            'sort_weight' => 40
        ));
    }

} 