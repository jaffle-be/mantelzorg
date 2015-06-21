<?php namespace Data;

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use \App\Questionnaire\Answer;
use \App\Questionnaire\Questionnaire;
use \App\Questionnaire\Session;

class SurveyAnswers extends Seeder
{

    public function run()
    {
        $faker = Faker::create();

        $questionnaire = Questionnaire::with(array('panels', 'panels.questions', 'panels.questions.choises'))->find(1);

        Session::chunk(200, function ($results) use ($questionnaire, $faker) {
            foreach ($results as $result) {
                $this->loopTroughQuestions($faker, $result, $questionnaire);
            }
        });
    }

    private function loopTroughQuestions($faker, $session, $questionnaire)
    {
        foreach ($questionnaire->panels as $panel) {
            foreach ($panel->questions as $question) {
                $answer = Answer::create([
                    'session_id'  => $session->id,
                    'question_id' => $question->id,
                    'explanation' => $faker->boolean() ? $faker->realText() : null,
                ]);

                $choises = $question->choises;

                if ($question->multiple_choise) {
                    if ($question->multiple_answer) {
                        $picked = $choises->random(rand(1, $choises->count()));

                        if (!is_array($picked)) {
                            $picked = array($picked);
                        }
                    } else {
                        $picked = array($choises->random(1));
                    }

                    $picked = new Collection($picked);

                    $answer->choises()->sync($picked->lists('id'));
                }

            }
        }
    }

}