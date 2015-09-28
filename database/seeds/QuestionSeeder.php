<?php

use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
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
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Taken mantelzorger',
            'question'               => 'Ik zou graag een beeld krijgen van de zorgsituatie en hoe u als mantelzorger zich hierbij voelt. Welke taken neemt u op in de zorg? Waarbij helpt u zoal?',
            'multiple_choise'        => 1,
            'multiple_answer'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Intensiteit van de mantelzorg',
            'question'               => 'Hoeveel tijd besteedt u ongeveer per dag of per week aan de zorg? Een ruwe inschatting is voldoende',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Duur van de mantelzorg',
            'question'               => 'Hoe lang zorgt u al voor uw oudere? Hoe komt het dat u deze zorg bent gaan opnemen?',
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Andere mantelzorgers of vrijwilligers',
            'question'               => 'Zijn er ook andere mantelzorgers of vrijwilligers die zorg opnemen voor uw oudere? Welke taken nemen zij op?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Professionele hulp aanwezig',
            'question'               => 'Zijn er professionele hulpverleners die u helpen in de zorg voor uw oudere? Welke taken nemen zij op?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Relatie met professionele hulp (samenwerking)',
            'question'               => 'Ervaart u een goede samenwerking met de hulpverleners?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Relatie met professionele hulp (ondersteuning)',
            'question'               => 'Voelt u zich ook persoonlijk ondersteund door deze hulp?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Relatie met professionele hulp (faciliteren relatie)',
            'question'               => 'Heeft de hulp gevolgen voor de relatie met uw oudere?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Overzicht belastende factoren',
            'question'               => 'Noteer hier de elementen die in het gesprek naar boven komen als belastend voor de mantelzorger wat de zorglening betreft.',
            'summary_question'       => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Overzicht ondersteunende factoren',
            'question'               => 'Noteer hier de elementen die in het gesprek naar boven komen als ondersteunend voor de mantelzorger wat de zorglening betreft.',
            'summary_question'       => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Gewenste verandering / ondersteuning',
            'question'               => 'Zijn er bepaalde zaken die de mantelzorger graag anders zou zien in de manier waarop de zorgverlening nu verloopt?',
            'summary_question'       => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 1,
            'title'                  => 'Mate van tevredenheid over zorgverlening',
            'multiple_choise'        => 1
        ));
    }

    protected function panel2()
    {
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Andere aandachtsgebieden',
            'question'               => 'Welke andere taken en verantwoordelijkheden heeft u nog naast de zorg? Lukt deze combinatie goed?',
            'explainable'            => 1,
            'multiple_choise'        => 1,
            'multiple_answer'        => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Voldoende tijd voor zichzelf',
            'question'               => 'Heeft u het gevoel dat u voldoende tijd overhoudt voor uzelf?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Impact zorg op eigen welbevinden',
            'question'               => 'Ervaart u soms dat de zorg gevolgen heeft voor uw gezondheid? Hoe voelt u zich momenteel?',
            'explainable'            => 1,
            'multiple_choise'        => 1,
            'multiple_answer'        => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Omgang met zorgsituatie (acceptatie) ',
            'question'               => 'Heeft u het gevoel dat u met de zorgsituatie hebt leren leven? Of is dat mogelijk voor u?',
            'explainable'            => 1,
            'multiple_choise'        => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Omgang met zorgsituatie (hantering) ',
            'question'               => 'Heeft u het gevoel dat u voldoende kennis hebt over de ziekte en hoe er mee om te gaan?',
            'explainable'            => 1,
            'multiple_choise'        => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Omgang met zorgsituatie (motivatie) ',
            'question'               => '',
            'explainable'            => 1,
            'multiple_choise'        => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Toekomstperespectief: grenzen aan zorg',
            'question'               => 'Denkt u wel eens na over de toekomst? Waar ligt voor u de grens bij het geven van de zorg?',
            'explainable'            => 1,
            'multiple_choise'        => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Toekomstperespectief: houding t.o.v woonzorgcentrum',
            'question'               => 'Heeft u al nagedacht over de mogelijkheid van een Woon- en Zorgcentrum? Hoe staat de oudere hier tegenover?',
            'explainable'            => 1,
            'multiple_choise'        => 1
        ));


        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Overzicht belastende factoren',
            'question'               => 'Noteer hier de elementen die in het gesprek naar boven komen als belastend voor de mantelzorger.',
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Overzicht ondersteunende factoren',
            'question'               => 'Noteer hier de elementen die in het gesprek naar boven komen als ondersteunend voor de mantelzorger.',
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Gewenste verandering / ondersteuning',
            'question'               => 'Zijn er bepaalde zaken die u graag anders zou zien in de manier waarop de zorgverlening nu verloopt?',
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 2,
            'title'                  => 'Mate van tevredenheid over zorgverlening',
            'question'               => 'Ik heb nu een beeld van de zorgverlening rond de oudere en hoe u zich hierbij voelt. Zou u ook kunnen aangeven hoe tevreden u over het algemeen bent over hoe de zorgverlening loopt?',
            'multiple_choise'        => 1
        ));

    }

    protected function panel3()
    {
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Situatie oudere',
            'question'               => 'Heeft uw ... problemen in het uitvoeren van dagelijkse activiteiten? Heef hij/zij last van gedragsstoornissen, karakterveranderingen of cognitieve achteruitgang? Hoe is het voor uzelf om hiermee geconfronteerd te worden?',
            'multiple_choise'        => 1,
            'multiple_answer'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Aanvaarden hulp',
            'question'               => 'Hoe gaat uw ... om met de ziekte? Aanvaardt hij/zij hulp van u, anderen of professionele hulp?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Veranderingen in relatie met oudere: taakpatronen en rollen',
            'question'               => 'Is uw relatie met uw ... door het zorgen veranderd?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Veranderingen in relatie met oudere: activiteiten samen',
            'question'               => 'Hebben jullie nog de mogelijkheid om samen activiteiten te ondernemen?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Kwaliteit relatie met oudere: wederkerigheid',
            'question'               => 'Hoe loopt de relatie met uw ... doorgaans? Heeft u het gevoel dat u waardering krijgt?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Kwaliteit relatie met oudere: mate van conflict',
            'question'               => 'Heeft u regelmatig conflicten met uw ... ? Indien ja, waarover gaan deze conflicten?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Kwaliteit relatie met oudere: mate van affectie',
            'question'               => 'Kunt u bij hem/haar terecht met uw eigen zorgen? Welke positieve zaken ervaart u in de relatie?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Sociaal netwerk: veranderingen in sociale relaties',
            'question'               => 'Is uw relatie met andere gezinsleden, vrienden en familie door de verzorging veranderd?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Sociaal netwerk: sociale steun',
            'question'               => 'Ervaart u voldoende steun en waardering? Van wie?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Sociaal netwerk: mate van conflict',
            'question'               => 'Ervaart u bepaalde conflicten met uw omgeving door de zorgsituatie? Met wie?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Overzicht belastende factoren',
            'question'               => 'Noteer hier de elementen die in het gesprek naar boven komen als belastend voor de mantelzorger.',
            'summary_question'       => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Overzicht ondersteunende factoren',
            'question'               => 'Noteer hier de elementen die in het gesprek naar boven komen als ondersteunend voor de mantelzorger.',
            'summary_question'       => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Gewenste verandering/ondersteuning',
            'question'               => 'Zijn er bepaalde zaken die u graag anders zou zien in de relatie met de ouderen of met andere personen',
            'summary_question'       => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 3,
            'title'                  => 'Mate van tevredenheid over de relaties',
            'question'               => 'Ik heb nu een beeld van de relatie met de oudere en andere personen in impact van de zorg hierop. Zou u ook kunnen aangeven hoe tevreden u over het algemeen bent over deze relaties?',
            'multiple_choise'        => 1,
        ));
    }

    public function panel4()
    {
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Huisvesting: aangepaste woning',
            'question'               => 'Is de woonsituatie geschikt voor de zorgverlening',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Huisvesting: woning voldoende veilig',
            'question'               => 'Vindt u de woning voldoende veilig voor uw ...?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Mobiliteit: eigen verplaatsing voor zorg',
            'question'               => 'Dient u zich ver te verplaatsen om te kunnen zorgen voor uw ...?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Mobiliteit: problemen verplaatsen binnenshuis',
            'question'               => 'Kan uw ... zich nog verplaatsen binnen de woning?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Mobiliteit: problemen verplaatsen buitenshuis',
            'question'               => 'Kan uw ... zich nog verplaatsen buiten de woning?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Hulpmiddelen',
            'question'               => 'Maakt u gebruik van een van de volgende hulpmiddelen bij de zorg? Wat zijn uw ervaringen hierbij?',
            'multiple_choise'        => 1,
            'explainable'            => 1,
            'multiple_answer'        => 1,
        ));
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Financiële situatie: inkomensverlies',
            'question'               => 'Ervaart u inkomensverlies bijvoorbeeld doordat u tijdelijk minder bent gaan werken?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Financiële situatie: kosten door zorg',
            'question'               => 'Heeft u hogere uitgaven door kosten die samenhangen met de zorg?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Financiële situatie: gebruik tegemoetkomingen',
            'question'               => 'Maakt u reeds gebruik van bepaalde financiële tegemoetkomingen in de thuiszorg?',
            'multiple_choise'        => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Overzicht belastende factoren',
            'question'               => 'Noteer hier de elementen die in het gesprek naar boven komen als belastend voor de mantelzorger.',
            'summary_question'       => 1,
            'explainable'            => 1
        ));
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Overzicht ondersteunende factoren',
            'question'               => 'Noteer hier de elementen die in het gesprek naar boven komen als ondersteunend voor de mantelzorger.',
            'summary_question'       => 1,
            'explainable'            => 1
        ));
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Gewenste verandering / ondersteuning',
            'question'               => 'Zou u iets aan de woonsituatie of de inrichting willen veranderen? Zijn er bepaalde zaken die het voor u makkelijker zouden maken om uw ... te helpen bij de verplaatsing binnen of buiten de woning? Zou u graag meer informatie willen hebben over financiële regelingen en tegemoetkomingen in de kosten?',
            'summary_question'       => 1,
            'explainable'            => 1
        ));

        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 4,
            'title'                  => 'Mate van tevredenheid over omgevingsfactoren',
            'question'               => 'Ik heb nu een beeld van de woonomgeving en de financiële aspecten van de zorgsituatie. Zou u ook kunnen aangeven hoe tevreden u over het algemeen bent hierover?',
            'summary_question'       => 1,
            'multiple_choise'        => 1
        ));

    }

    public function panel5()
    {
        \App\Questionnaire\Question::create(array(
            'questionnaire_id'       => 1,
            'questionnaire_panel_id' => 5,
            'title'                  => 'Algemene inschatting beleving zorgsituatie',
            'question'               => 'Vraag de mantelzorger naar een algemene inschatting van de zwaarte van de zorgsituatie.',
            'summary_question'       => 1,
            'multiple_choise'        => 1
        ));
    }

} 