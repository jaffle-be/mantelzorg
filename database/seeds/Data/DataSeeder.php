<?php namespace Data;

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{

    public function run()
    {
        $this->call('Data\Registrations');
        $this->call('Data\Users');
        $this->call('Data\Mantelzorgers');
        $this->call('Data\Ouderen');
        $this->call('Data\SurveySessions');
        $this->call('Data\SurveyAnswers');
    }

}