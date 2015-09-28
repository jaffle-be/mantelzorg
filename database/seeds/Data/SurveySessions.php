<?php namespace Data;

// Composer: "fzaninotto/faker": "v1.3.0"
use App\Questionnaire\Session;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Database\Seeder;

class SurveySessions extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        Oudere::chunk(250, function ($ouderen) use ($faker) {
            $ouderen->load('mantelzorger');

            foreach ($ouderen as $oudere) {
                $this->addData($oudere, $faker);
            }
        });
    }

    private function addData($oudere, $faker)
    {
        /** @var Generator $faker */
        $date = $faker->dateTimeBetween('-3 months', 'now');

        $session = new Session([
            'user_id'          => $oudere->mantelzorger->hulpverlener_id,
            'oudere_id'        => $oudere->id,
            'mantelzorger_id'  => $oudere->mantelzorger_id,
            'questionnaire_id' => 1,
            'created_at'       => $date,
            'updated_at'       => $date,
        ]);

        $session->save(['timestamps' => false]);
    }

}