<?php namespace Data;

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Mantelzorger\Oudere;
use Questionnaire\Session;

class SurveySessions extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        $ouderen = Oudere::chunk(250, function ($ouderen) use ($faker) {
            $ouderen->load('mantelzorger');

            foreach ($ouderen as $oudere) {
                $this->addData($oudere, $faker);
            }
        });
    }

    private function addData($oudere, $faker)
    {
        Session::create([
            'user_id'          => $oudere->mantelzorger->hulpverlener_id,
            'oudere_id'        => $oudere->id,
            'mantelzorger_id'  => $oudere->mantelzorger_id,
            'questionnaire_id' => 1,
        ]);
    }

}